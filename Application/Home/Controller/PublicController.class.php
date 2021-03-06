<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller{
	public function __construct(){
		parent::__construct();
	}

	public function login(){
		$this->display('Default/login');
	}

	public function user_login(){
		$user_info = session('RZD_USER');
		if (!empty($user_info)) {
			$this->redirect('home/user/upload');
		} else {
			$user_name = I('user_name');
			$password = I('password');
			$user = D('user');
			$result = $user->login($user_name, $password);
			if ($result) {
				$redirect = session('request') ? session('request') : 'home/default/index';
				$this->success('登录成功!', $redirect);	
			}else{
				$this->error('登录失败!');
			}
		}
		
	}

	public function test(){
		$user_model = D('user');
		echo  $user_model->inster_login_info();
	}
}
