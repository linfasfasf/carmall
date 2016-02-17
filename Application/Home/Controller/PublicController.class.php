<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller{
	public function __construct(){
		parent::__construct();
	}

	public function login(){
		$this->display('default/login');
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
				var_dump(session('user_info'));	
			}else{
				echo 'login fail';
			}
		}
		
	}
}