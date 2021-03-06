<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model{
    protected $_validate = array(
        array('email','require','email require '),
        array('email','email','邮箱格式不符合'),
        array('emial',array('254430304@qq.com'),'zaizheli ',2,'in'),
    );
    
    /*
     * 获取登陆信息
     */
    public function get_user(){
        $username = I('post.identity');
        $password = I('post.password');
        $user = M('User');
        $res = $user->where("username='%s' AND password = '%s'",array($username,$password))->find();
        return $res;
    }
    
    /*
     * 保存文件的路径
     */
    public function save_file($path,$file_name =''){
        var_dump(session('user_name'));
        $username = session('user_name');
        if(!$username){
            $this->error('您还未登陆！',U('Home/User/index'));
        }
        $user = M('user_file');
        $data['username'] = $username;
        $data['path'] = $path;
        $data['filename']=$file_name;
        $result = $user -> data($data)->add();
        var_dump($result);
        if(!$result){
            return FALSE;
        }
        return TRUE;
    }
    
    /*
     * 获取该用户所有上传的文件
     */
    public function get_user_file(){
        $user_name = session('user_name');
        $user_file = M('user_file');
        $res = $user_file->where("username='%s'",array($user_name))->field('path,filename,title,id')->select();
        return $res;
    }

    /*
     * 根据id进行删除
     */
    public function delete_article($id){
        $user = M('user_file');
        return $user->where('id=%d',$id)->delete();
    }

    /*
     * 检测手机是否存在
     * 未启用，需数据库添加字段
     */
    public function check_mobile(){
        $user = M('User');
        $data['mobile'] = I('mobile');
        $data['username']  = I('email');
        $data['_logic'] ='OR';
        $result = $user->field('id')->where($data)->select();
        if($result){
            return true;
        }
        return false;
    }
    /*
     * 插入数据库，成功返回true
     */
    public function create_account($email,$mobile,$password,$name){
        $user = M('User');
        $data['username']   = $email;
        $data['password']   = md5($password);
        $data['mobile']     = $mobile;
        $data['nickname']   = $name;
        if(!$user->add($data)){
            return false;
        }
        return true;
    }

    public function get_user_id_unique(){
        $user_id = creat_rand_str(6, 'numeric');
        $user_model = D('user');
        if ((!$user_model->where('uid =%d',$user_id)->find()) &&  (strlen(floor($user_id)) == 6)) {
            return $user_id;
        }
        return $this->get_user_id_unique();
    }


    public function inster_login_info(){
        $username = I('user');
        $password = I('pass');
        $password = md5($password);
        $map['uid']       = $this->get_user_id_unique();
        $map['last_login']= get_client_ip();
        $map['user_name'] = $username;
        $map['password']  = $password;
        $user_model = D('user');
        $result = $user_model->add($map);
        if ($result) {
            echo "insert success";
        }else{
            echo "insert fail";
        }

    }
    /*
     * 验证登录账号以及密码，记录登录信息到session
     */
    public function login($username,$password){
        $user             = M('User');
        $map['user_name']  = $username;
        $map['password']  = $password;
        $result =$user->where($map)->field('user_name, uid')->select();
        if ( !is_null($result[0])) {
            $data['last_login'] = date("Y-m-d H:i:s");
            $data['last_ip']    = get_client_ip();
            $info = $user->where('uid = %d', $result[0]['uid'])->save($data);
            session('RZD_USER.uid', $result[0]['uid']);
            session('RZD_USER.user_name', $result[0]['user_name']);
            return true;
        }else{
            return false;
        }
    }


}
