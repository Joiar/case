<?php
namespace Home\Controller;

use Think\Controller;

class DetailsController extends Controller
{
    protected $data = array();

    public function index()
    {
        $this->detail();
        $this->detail2();
        $this->product();
    }

    // 产品
    public function product()
    {
        $product = M('arctype as a')->join('__ARCHIVES__ as c on a.id = c.typeid')->field('c.id, title,litpic,c.description')->where(array('reid' => 0,'a.id' => 2))->limit(4)->order('rand()')->select();
        $this->data['product'] =$product;


    }

    public function detail()
    {
        $id = I('id');
        $detail = M('archives as b')
                ->join('__CHANPIN__ as c on b.id = c.aid')
                ->where(array('b.id'=>$id,'c.aid'=>$id))
                ->field('*')->find();
        $this->data['detail'] = $detail;
        // dump(M()->getLastSQL());

    }

    public function detail2()
    {
        $id = I('id');
        $detail2 = M('chanpin')
                ->where(array('aid'=>$id))
                ->field('*')->find();


        //处理属性
        $m = M('channeltype')->where(array('id'=>17))->getField('fieldset',true);
        $m = explode(":", $m[0]);
        $arr = array();
        foreach($m as $k=>$v){
            if($k != 0 and $k % 2 == 0){
                $vv = explode("\n",$v);
                $f = str_replace('>','',$vv[0]);
                preg_match('/itemname="(.*?)"/', $m[$k-1],$s);
                $arr[$f] = $s[1];
            }
        }
        $attr = array();
        foreach($detail2 as $ks=>$vs){
            foreach ($arr as $key => $value) {
                if(trim($ks) == trim($key) and $ks != 'body'){
                    $attr[$value] = $vs;
                }

            }
        }
        $detail2['attr'] = $attr;
        $this->data['detail2'] = $detail2;


    }

    // 转化数据为json格式
    public function __destruct()
	{
        // dump($this->data);exit;
		echo json_encode($this->data);
	}
}
