<?php
namespace Home\Controller;
use Think\Controller;
class DefaultController extends Controller {
    /*
     * 显示首页信息
     */
    public function index(){
        $product_info = M('product_info');

        $query = 'select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  b.default =1';
        $result = $product_info->query($query);
//        var_dump($result);
        $this->assign('product_info',$result);
        $this->display();
        
    }

    public function show_product(){
        $product_id = I('product_id');
        $product_info = M('product_info');
        $result = $product_info->where('product_id=%d',$product_id)->select();
        $pic_info_model = M('pic_info');
        $pic_info = $pic_info_model->where('product_id =%d',$product_id)->select();
        $this->assign('pic_info',$pic_info);
        $this->assign('product_info',$result[0]);
        // var_dump($result);die();
        $this->display();
    }

    public function login(){
        I('get.p');

        $this->display();
    }


    public function upload(){
        $num = I('get.file_num');
        $num = is_numeric($num)?$num:4;

        $this->assign('file_num',$num);
        $this->display();
    }

    public function upload_file(){
        $title     = I('title');
        $content   = I('content');
        // $title  = substr($title, 9,-10);
        // $title  = substr($title, 0,-9);
        // var_dump($title);
        // die();
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

    public function test(){
        echo creat_rand_str(6,'numeric').PHP_EOL;
        echo $this->get_product_id();
    }

    /*
     * 首页商品信息显示下一页
     */
    public function next_page(){
        $page = I('get.p');
        $tea = D('Home/Guanyintea');
        $len =C('PAGE_SHOW_NUM');
        $product_info = $tea->get_product_info_page($page,$len);
        $count        = $tea->get_product_total_num();
        if(count($product_info)==0 && $count ==0){
            $product_info = '';
        }
        $this->assign('current_page',$page);
        $this->assign('total_product',$count);
        $this->assign('total_page_add',intval(ceil($count/6)+1));
        $this->assign('product_info',$product_info);
        $this->display('index');
    }

    public function order_by(){
        $tea          = D('Guanyintea');
        $len          = C('PAGE_SHOW_NUM');
        $product_info = $tea->order_by($len);
        $count        = $tea->get_product_total_num();

        $this->assign('current_page',$p=1);
        $this->assign('total_product',$count);
        $this->assign('total_page_add',intval(ceil($count/6)+1));
        $this->assign('product_info',$product_info);
        $this->display('index');
    }




    public function testajax(){
        $product_id = I('value');
        echo 1;
    }

}