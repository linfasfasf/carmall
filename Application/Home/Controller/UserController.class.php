<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends AdminController{
    
    public function __construct() {
        parent::__construct();
        
    }
    
    public function index(){
        $this->display('Default/login');
    }

    public function creat_group(){
        $group_name = I('post.group_name','','htmlspecialchars');
        $group_id  =  $this->get_uniqid_id('group_info',4);
        $group_model = M('group_info');
        $data['group_id'] = $group_id;
        $data['group_name'] = $group_name;
        if ($group_model->add($data)) {
            $this->success('新增分组成功!');
        }else{
            $this->error('新增分组失败!');
        }

    }

    public function upload(){
        $num = I('get.file_num');
        $num = is_numeric($num)?$num:4;
        $group_model = M('group_info');
        $result = $group_model->select();
        $this->assign('group',$result);
        $this->assign('file_num',$num);
        $this->display('Default/upload');
    }

    public function upload_file(){
        $title     = I('title');
        $content   = I('content');
        $group_id  = I('group');
        $upload  = new \Think\Upload();
        $pic_info = M('pic_info');
        $product_info_model = M('product_info');
        $product_id = $this->get_uniqid_id('product_info');
        $upload->maxSize   = 0 ;
        $upload->exts      = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath  = './Uploads/product/';
        $upload->savePath  = $product_id.'/';
        // $upload->saveName  = 'time().'_'.mt_rand()';
        $upload->autoSub   = false;
        //上传文件
        $info  = $upload->upload();
        if (!$info) {
            $this->error($upload->getError());
        }else{
            foreach ($info as $key => $file) {
                $file_name =$this->img_check('./Uploads/product/'.$product_id.'/'.$file['savename']);
                $data['product_id'] = $product_id;
                $data['pic_name']   = $file_name;
                if ($key == 'pic0') {
                    $data['default'] =1;
                }else{
                    $data['default'] = 0;
                }
                $product_result = $pic_info->add($data);
            }
        }
        $info['title']      = $title;
        $info['content']    = $content;
        $info['product_id'] = $product_id;
        $info['group_id']   = $group_id;
        $pic_result = $product_info_model->add($info);
        if ($product_result && $pic_result) {
            $this->success('上传成功','/Home/editor/editor?product_id='.$product_id);    
        } else {
            $this->error('上传失败!');
        }
        
    }

    private function get_uniqid_id($model_name,$len=6){
        while(strlen($product_id = intval(creat_rand_str(6,'numeric'))) != 6);
        $product_info_model = M($model_name);
        if ($model_name == 'group_info') {
            $field = 'group_id';
        }else{
            $field = 'product_id';
        }
        $product_info  = $product_info_model->where($field.' = %d',$product_id)->select();
        if(!empty($product_info)){
            $product_id = $this->get_uniqid_id($model_name,$len);
        }
        return $product_id;
    }
    
    public function img_check($file){
        $file_name = substr($file, strrpos($file, '/')+1);
        $path      = substr($file, 0,strrpos($file, '/'));

//        $image = new \Think\Image();
//        $image->open($file);
//        $width = $image->width();
//        if ($width >= 720) {
//            // $file = $path.'/thumb'.$file_name;
//            $file_name = 'thumb'.$file_name;
//            $image->thumb(720,1080)->save($path.'/'.$file_name);
//        }

        return $file_name;
    }

    public function test(){
        echo $this->get_uniqid_id('product_info');
    }
    /**
     * 进行登陆
     *
     */
    public function login(){
        $user_name = I('username');
        $pssword   = I('password');
        if(empty($user_name)|empty($pssword)){
            $this->error("登录失败");
        }
        $user   = D('User');
        $result  = $user->login($user_name,$pssword);
        if($result){
            $msg    = '登录成功！';
        }else{
            $msg    = '登录失败，请重新登录。';
        }

        $this->assign('msg',$msg);
        $this->display('Index/login');
    }


    public function isLogin()
    {
        $sessionId = session("id");
        if(!empty($sessionId)){
            return true;
        }
        return false;
    }


    public function lero_login(){
        $username = I('identity');
        $password = I('password');

        $user = M('User');
        $user_info = $user->where('username=%d AND password =%d',array($username,$password))->field('username')->select();
        session('user_name',$user_info);
        redirect('/Home/Lero/index');
    }

    /*
     * 注册账号
     */
    public function create_account(){
        $email      = I('email');
        $mobile     = I('mobile');
        $password   = I('password');
        $repassword = I('confirmation');
        $name       = I('name');

        if(empty($email)|empty($mobile)|empty($password)|empty($repassword)|empty($name)){
            $this->error('信息不能为空','/Home/User/register',3);
        }
        if($password!==$repassword){
            $this->error('两次输入的密码不一致','/Home/User/register',3);
        }
        if(!$this->check_email_mobile($email)){
            $this->error('请输入未注册的手机或邮箱','/Home/User/register',3);
        }

        $user = D('User');
        if(!$user->create_account($email,$mobile,$password,$name)){
            $msg = '注册失败，请重试！';
        }else{
            $msg = '恭喜，注册成功！';
        }

        $this->assign('msg',$msg);
        $this->display('Index/register');
    }
    
    /*
     * 登出
     */
    public function logout(){
        session('RZD_USER',NULL);
        echo 'logout success';
    }

    /*
     * 跳转到注册界面
     */
    public function register(){
        $this->display('Index/register');
    }

    /*
     * 邮箱验证
     * 通过验证返回 true
     */
    public function check_email_mobile($email){
        if(empty($email)){
            return false;
        }
        var_dump($email);
        $pattern = "/\w+@(\w|\d)+\.\w{2,3}/i";
        if(preg_match_all($pattern,$email,$match)){
            $user = D('User');
            if( !$user->check_mobile()){
                return true;
            }
        }
        return false;
    }

    public function check_mobile(){
        $user = D('User');
        if(!$user->check_mobile()){
            return false;
        }
        return true;
    }
}
