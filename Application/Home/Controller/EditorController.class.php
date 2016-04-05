<?php
namespace Home\Controller;
use Think\Controller;
class EditorController extends AdminController {

     public function __construct() {
        parent::__construct();
        
    }

    public function index(){
       $product_info = M('product_info');

        $query = 'select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  b.default =1';
        $result = $product_info->query($query);
        $this->assign('product_info',$result);

        $group_model = M('group_info');
        $group = $group_model->select();        
        $this->assign('group',$group);

        $this->display('index');

    }

    public function editor(){
        $product_id = I('product_id');
        $product_info = M('product_info');
        $group_model = M('group_info');
        $group = $group_model->select();
        
        $result = $product_info->where('product_id=%d',$product_id)->select();
        $pic_info_model = M('pic_info');
        $pic_info = $pic_info_model->where('product_id =%d',$product_id)->select();
        $this->assign('pic_info',$pic_info);
        $this->assign('group',$group);
        $this->assign('product_info',$result[0]);
        // var_dump($result);die();
        $this->display();


    }

    public function del_product(){
        $product_id   = I('product_id');
        $product_info_model     = M('product_info');
        $pic_info_model            = M('pic_info');

        $res1  = $product_info_model->where('product_id =%d', $product_id)->delete();
        $res2  = $pic_info_model->where('product_id =%d', $product_id)->delete();

        if($res ){
            $this->success('删除成功！', '/home/editor/index');
        }else{
            $this->error('删除失败！');
        }
    }

    public function add_img(){
        $product_id     = I('product_id');
        $upload  = new \Think\Upload();
        $pic_info = M('pic_info');
        
        
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
                $file_name =UserController::img_check('./Uploads/product/'.$product_id.'/'.$file['savename']);
                $data['product_id'] = $product_id;
                $data['pic_name']   = $file_name;
                
                
                    $data['default'] = 0;
                
                $product_result = $pic_info->add($data);
            }
        }
        
        if ($product_result ) {
            $this->success('上传成功','/Home/editor/editor?product_id='.$product_id);    
        } else {
            $this->error('上传失败!');
        }
    }


    public function del_img(){
        $product_id  = I("product_id");
        $pic_name   = I('pic_name');
        $pic_info_model    = M('pic_info');
        $res    = $pic_info_model->where("pic_name = '%s'",  $pic_name)->delete();
        if (!$res) {
            $this->error('删除失败！', '/home/editor/editor?product_id='.$product_id);
        }else{
            $this->success('删除成功！', '/home/editor/editor?product_id='.$product_id);
        }

    }
    //修改产品名称和介绍
    public function modify(){
        $title      = I('title');
        $conntent   = I('content');
        $product_id  = I('product_id');
        $product_info_model  = M('product_info');
        $data['title']  = $title;
        $data['content']  = $conntent;
        $res = $product_info_model->where('product_id =%d', $product_id)->save($data);
        if($res){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }

}