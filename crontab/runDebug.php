<?php

/**
 * 执行测试脚本
 *
 * @author langhua
 */

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', realpath(dirname(dirname(__DIR__)) . DS));
define("APP_PATH", BP . DS . "app");
define("APP_CONF_PATH", APP_PATH . DS . "conf");

$app = new \Yaf\Application(APP_CONF_PATH . DS . "app.ini");
$app->bootstrap()->execute('main', $argv);

/**
 * 
 * 进程管理器
 *
 */
class WorkerManager
{
    const LOG_FILE = 'run_debug';
    protected $_wokerInfo = null;
    protected $_scriptRoot = '/var/www/dev/task/crontab';
    protected $_ouputRoot = '';
    protected $_workerInfos = null;
    protected $_exitFlag = false;


    /**
     * 处理子进程结束
     */
    public function workerExit($signo = null)
    {
        $arr = swoole_process::wait(false);
        if (! $arr) {
            return ;
        }
        
        $pid = $arr['pid'];
        if (empty($this->_workerInfos[$pid])) {
            return;
        }
                
        $workerInfo = $this->_workerInfos[$pid];
        
        // 取消事件监听
        $worker = $workerInfo['worker'];
        $pipe = $worker->pipe;
        
        // 因为exit事件可能在数据读完前触发，因此关闭文件的操作做延迟处理
        $outputFile = $workerInfo['output_file'];
        swoole_timer_after(1000*5, function () use($outputFile, $pipe){
            fclose($outputFile);
            @ swoole_event_del($pipe);
        });
        
        // 标记任务为：运行结束
        try {
            $logM = \Debug\Mapper\RunlogModel::getInstance();
            $dbRow = $workerInfo['info'];
            $dbRow->status = 2;
            $dbRow->endtime = time();
            $logM->update($dbRow);
        }
        catch (\Exception $e) {
            // TODO: for update error
        }
        
        unset($this->_workerInfos[$pid]);
    }
    
    /**
     * 初始化信号
     */
    protected function initSignal()
    {
        swoole_process::signal(SIGCHLD, [$this, "workerExit"]);
        swoole_process::signal(SIGTERM, [$this, "gracefulExit"]);
    }
    
    /**
     * 进程优雅退出
     */
    public function gracefulExit($signo)
    {
        $this->_exitFlag = true;
    }
    
    /**
     * 任务启动
     */
    public function workerStart($worker)
    {
        try {
            $filename = $this->_wokerInfo->filename;
            $fullname = $this->_scriptRoot . '/' . $filename;
            $param = $this->_wokerInfo->param;
            $params = explode(' ', $param);
            array_unshift($params, $fullname);
            
            $worker->exec("/usr/local/php/bin/php", $params);
        }
        catch (\Execption $e) {
            \Hlg::logException($e, self::LOG_FILE);
        }
    }
    
    /**
     * 关联子进程写事件，重定向到输出文件
     */
    protected function attachWorkerIO($worker, $dbRow)
    {
        $logID = $dbRow->logId;
        $ouputFilePath = sprintf('%sres_%s.txt', $this->_ouputRoot, $logID);
        $ouputFile = fopen($ouputFilePath, 'w');
        
        swoole_event_add($worker->pipe, function($pipe) use ($worker,$ouputFile) {
            $recv = $worker->read();
            fwrite($ouputFile, $recv);
        });
        
       return $ouputFile;
    }
    
    /**
     * 定期检查是否有需要执行任务
     * 考虑到目前任务表的大小，采取全取扫描。
     */
    public function checkTask()
    {
        if ($this->_exitFlag && empty($this->_workerInfos)) {
            exit(0);
        }
        
        try {
            $logM = \Debug\Mapper\RunlogModel::getInstance();
            $log = $logM->where(['status'=>0])->first();
            if (empty($log)) {
                return;
            }

            // 标记为：运行态
            $log->status = 1;
            $logM->update($log);
            $this->_wokerInfo = $log;
        }
        catch (\Execption $e) {
            \Hlg::logException($e, self::LOG_FILE);
        }
        
        if (empty($log)) {
            return;
        }
        
        $worker = new swoole_process([$this, 'workerStart'], true);
        $ouputFile = $this->attachWorkerIO($worker, $log);
        $pid = $worker->start();
        $now = time();
        $workerInfo = ['pid'=>$pid, 'worker'=>$worker, 'info'=>$log, 'output_file'=>$ouputFile, 'start_time' => $now];
        $this->_workerInfos[$pid] = $workerInfo;
    }
    
    /**
     * start
     */
    public function start()
    {
        // 输出文件目录
        $this->_ouputRoot = BP . DS . "www/output/";
        if (! file_exists($this->_ouputRoot)) {
            mkdir($this->_ouputRoot, 0777, true);
        }
        
        $this->loadConfig();
        $this->initSignal();
        swoole_process::daemon(true,true);
        swoole_timer_tick(800, [$this, 'checkTask']);
    }




}



class db
{

    protected $_dbHost = '127.0.0.1';
    protected $_dbName = 'task';
    protected $_dbUserName = 'lero';
    protected $_dbPassword = "Lys920507";
    protected $_connection;
    /**
     * pdo连接数据库
     *
     * @param unknown $module
     * @throws Exception
     * @return \PDO
     */
    public function connect($module)
    {
        $dsn = 'mysql:host=' . $this->_dbHost . '; dbname=' . $this->_dbName;
        if (empty($this->_connection)) {
            $retry = 3;
            while ($retry) {
                $retry --;
                try {
                    $this->_connection = new PDO($dsn, $this->_dbUserName, $this->_dbPassword, array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    ));
                    $this->_connection->query("SET NAMES utf8");
                    break;
                } catch (PDOException $e) {
                    $code = $e->getCode();
                    if (! in_array($code, array(
                        1031,
                        1032,
                        1033,
                        1045
                    ))) {
                        // 如果不是用户名，密码相关错误，失败重连
                        continue;
                    }
                    throw new Exception('pdo connection failed.' . $e->getMessage());
                    break;
                    
                }
            }
        }
        return $this->_connection;
    }

    public function query()
    {
        $stmt = $this->_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


function main($argv)
{
    $manager = new WorkerManager();
    $manager->start();
}

?>
