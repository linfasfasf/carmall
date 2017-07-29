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
        $this->assign('product_info',$result);

        $group_model = M('group_info');
        $group = $group_model->select();


        $groupMod = M('group_info');
        $groupInfo= $groupMod->where("group_name = '新闻'")->select();
        $groupId  = $groupInfo[0]['group_id'];
        $productInfo = M('product_info');
        $query    = "select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  a.group_id={$groupId}";
        $result   = $productInfo->query($query);

        $this->assign('news', $result);
        $this->assign('group',$group);
        $this->display();
        
    }

    public function show_introduce(){
        $group_model = M('group_info');
        $group = $group_model->select();
        $this->assign('group',$group);
        $this->display();
    }



    public function show_news()
    {
        $groupMod = M('group_info');
        $groupInfo= $groupMod->where("group_name = '新闻'")->select();
        $groupId  = $groupInfo[0]['group_id'];
        $productInfo = M('product_info');
        $query    = "select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  a.group_id={$groupId}";
        $result   = $productInfo->query($query);

        $this->assign('news', $result);
        $this->display();
    }

    public function show_news_detail()
    {
        $productId = I('news_id', 0);
        $productInfo = M('product_info');
        $query = "select a.product_id , a.title, a.content, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id AND a.product_id={$productId}";
        $result = $productInfo->query($query);
        $this->assign('news', $result[0]);
        $this->display();
    }

    public function show_product(){
        $product_info = M('product_info');
        $groupId = I('group_id');
        $query = 'select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  b.default =1 ';
        if(!empty($groupId)&& is_numeric($groupId)){
            $query .= "and a.group_id=".$groupId;
        }
        $result = $product_info->query($query);
        $this->assign('product_info',$result);

        $group = M('group_info');
        $query = "SELECT * FROM group_info";
        $result = $group->query($query);
        $this->assign('group_info', $result);

        $this->display();
    }

    public function show_product_detail()
    {
        $productId = I('product_id', 0);
        $productInfo = M('product_info');
        $query = "select a.product_id , a.title, a.content, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id AND a.product_id={$productId}";
        $result = $productInfo->query($query);
        $this->assign('product_info', $result[0]);
        $this->display();
    }


    public function show_promotion()
    {
        $this->display();
    }

    public function show_partner()
    {
        $groupMod = M('group_info');
        $groupInfo= $groupMod->where("group_name = '合作伙伴'")->select();
        $groupId  = $groupInfo[0]['group_id'];
        $productInfo = M('product_info');
        $query    = "select a.product_id , a.title, b.pic_name from product_info a, pic_info b where a.product_id = b.product_id and  a.group_id={$groupId}";
        $result   = $productInfo->query($query);

        $this->assign('news', $result);
        $this->display();
    }

    public function show_recruit()
    {
        $this->display();
    }



    public function show_group(){
        $group_id = I('group_id');
        $product_info = M('product_info');
        $query = 'select a.product_id , a.title, b.pic_name 
        from product_info a, pic_info b where a.product_id = b.product_id and  b.default =1 and a.group_id ='.$group_id;
        $result = $product_info->query($query);
        $this->assign('product_info',$result);

        $group_model = M('group_info');
        $group = $group_model->select();        
        $this->assign('group',$group);
        $this->display('index');
        
    }



    public function login(){
        I('get.p');

        $this->display();
    }



    public function contact_us(){
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
        $upload  = new \Think\Upload();
        $pic_info = M('pic_info');
        $product_info_model = M('product_info');
        $product_id = $this->get_product_id();
        $upload->maxSize   = 0 ;
        $upload->exts      = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath  = './Uploads/product/';
        $upload->savePath  = $product_id.'/';
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

    public function test()
    {
        $phpexcel_obj = get_phpexcel();
        $time = date('H-i-s');
        $phpexcel_obj->getProperties()
                     ->setCreator("carmall")
                     ->setLastModifiedBy("carmall")
                     ->setTitle($time)
                     ->setSubject("Test Document")
                     ->setDescription("descript")
                     ->setKeywords("office")
                     ->setCategory("test file");
        $pic_info = M('pic_info');
        $result = $pic_info->select();
        //设置标题栏
        $phpexcel_obj->setActiveSheetIndex(0)
                     ->setCellValue('A1','id')
                     ->setCellValue('B1','product_id')->setCellValue('C1','pic_name')->setCellValue('D1','default');

        $line ='2';
        foreach($result as $pic_info){
            $char = 'A';
//            $phpexcel_obj->setActiveSheetIndex(0)
//                        ->setCellValue($char++.$line, $pic_info['id'])
//                        ->setCellValue($char++.$line, $pic_info['product_id'])->setCellValue($char++.$line, $pic_info['pic_name'])->setCellValue($char.$line, $pic_info['default']);
            foreach($pic_info as $value){
                $phpexcel_obj->setActiveSheetIndex(0)->setCellValue($char++.$line, $value);
            }
            $line++;
        }

        $writer_obj = \PHPExcel_IOFactory::createWriter($phpexcel_obj, 'Excel2007');
        $writer_obj->save('C:/Users/Administrator/Desktop/php/'.$time.'.xlsx');
        echo 'yes';
    }

    public function sendMail(){
        send_mail('254430304@qq.com', 'test', '这是测试邮件');
    }



}