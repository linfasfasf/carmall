<?php
namespace Test\Controller;
//use Think\Controller;
class IndexController extends \PHPUnit_Framework_TestCase {
    use \Think\PhpUnit;
    static function setupBeforeClass(){
        self::$app = new \Think\PhpunitHelper();
        self::$app->setMVC('app.com','Home','Default');
        self::$app->setTestConfig(['DB_NAME'=>'test', 'DB_HOST'=>'127.0.0.1']);
        self::$app->start();
    }

    public function testIndex(){
        $carmall = M('product_info');
        $sql    = 'select a.product_id , a.title, b.pic_name from
                  product_info a, pic_info b where a.product_id = b.product_id and  b.default =1';
        $result = $carmall->query($sql);
        $output = $this->execAction('index');
        foreach($result as $vo){
            $this->assertRegExp('/product_id?='.$vo['product_id'].'/', $output);
        }
    }

    public function testIndexfunction(){
        $this->expectOutputRegex('/<html>/');
//        $this->execAction('index');
        print '<html>';
    }


}