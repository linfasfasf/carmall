<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->display('Default/login');
    }



    public function upload(){
        $num = I('get.file_num');
        $num = is_numeric($num)?$num:4;

        $this->assign('file_num',$num);
        $this->display('default/upload');
    }

    public function upload_file(){
        $title     = I('title');
        $content   = I('content');
        $upload  = new \Think\Upload();
        $pic_info = M('pic_info');
        $product_info_model = M('product_info');
        $product_id = $this->get_product_id();
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
              
                $data['product_id'] = $product_id;
                $data['pic_name']   = $file['savename'];
                if ($key == 'pic0') {
                    $data['default'] =1;
                }else{
                    $data['default'] = 0;
                }
                $pic_info->add($data);
            }
        }
        $info['title'] = $title;
        $info['content'] = $content;
        $info['product_id'] =$product_id;
        $product_info_model->add($info);
        $this->success('上传成功','/Home/default/show_product?product_id='.$product_id);
    }

    private function get_product_id(){
        while(strlen($product_id = intval(creat_rand_str(6,'numeric'))) != 6);
        $product_info_model = M('product_info');
        $product_info  = $product_info_model->where('product_id = %d',$product_id)->select();
        if(!empty($product_info)){
            $product_id = $this->get_product_id();
        }
        return $product_id;
    }
    
    /*
     * 进行登陆
     * 
     *
     */
    public function login(){
        $refer_page = $_SERVER['HTTP_REFERER'];
        session('refer_page',$refer_page);
        $user_info = session('user_info');
        if(isset($user_info)){
            $msg        = '您已登录，无须重复登录！';
            $this->assign('refer_page',session('refer_page'));
            $this->assign('msg',$msg);
        }else{
            $user_name = I('username');
            $pssword   = I('password');
//            var_dump($user_name);
            if(empty($user_name)|empty($pssword)){
                $this->index();
                exit();
            }
            $user   = D('User');
            $result  = $user->login($user_name,$pssword);
            if($result){
                $msg    = '恭喜您，登录成功！';
                $this->assign('refer_page',session('refer_page'));
            }else{
                $msg    = '登录失败，请重新登录。';
            }
        }
        $this->assign('msg',$msg);
//        var_dump($msg);
        $this->display('Index/login');
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
        session('user_info',NULL);
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