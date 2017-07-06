<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends Controller
{
    public function getContent()
    {
        $id = intval(I('id'));
        $newsMod = M('news');
        $news = $newsMod->where("id =$id")->select();
        $this->assign('news', $news);
        $this->display();
    }

    public function addNews()
    {
        $title = I('title', '');
        $content = I('content', '');

        $newsMod             = M('news');
        $data['title']       = $title;
        $data['content']     = $content;
        $data['create_time'] = time();
        $newsMod->add($data);
        $this->success("增加成功", "/");
    }

    public function delNews()
    {
        $id = I('id', 0);
        $newsMod  = M('news');
        $newsMod->where("id = $id")->delete();
        $this->success("删除成功");
    }
}