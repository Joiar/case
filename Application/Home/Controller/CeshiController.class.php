<?php
namespace Home\Controller;

use Think\Controller;

class CeshiController extends Controller
{
    protected $data = array();

    public function index()
    {
        $this->ceshi();
    }

    public function ceshi()
    {
        $id = I('id');
        $new = M('addonarticle as a')->join('__ARCHIVES__ as b on a.aid = b.id')->where(array('a.aid'=>$id,'b.id'=>$id))->field('title,litpic,pubdate,body')->find();
        $new['pubdate'] = date('Y-m-d', $new['pubdate']);
        $this->data['archives'] = $new;
    }

    // 转化数据为json格式
    public function __destruct()
	{
        // dump($this->data);exit;
		echo json_encode($this->data);
	}
}
