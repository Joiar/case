<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    protected $data = array();

    public function index()
    {

        $this->arctype();
        $this->goodProduct();
        $this->hotProduct();
        $this->newProduct();
        $this->newAsk();
        $this->newWork();
        $this->newWorks();
        $this->banner();
        $this->icons();
        $this->copyrights();
        $this->arctypes();
    }

    // 查询导航分类
    public function arctype()
    {
        $arctype = M('arctype')->field('id, typename,defaultnames')->where(array('reid' => 0,'ishidden' => 0))->select();
        $this->data['arctype'] =$arctype;
    }

    // 查询隐藏航分类
    public function arctypes()
    {
        $arctypes = M('arctype')->field('id, typename,defaultnames')->where(array('reid' => 0,'ishidden' => 1,'issend' => 1))->select();
        $this->data['arctypes'] =$arctypes;
    }

    // 品牌营销
    public function icons()
    {
        $this->getData('icons', 19, 'a.id, title, writer, source, litpic');
    }

    // 版权信息
    public function copyrights()
    {
        $copyrights = M('arctype as a')->join('__ARCHIVES__ as c on a.id = c.typeid')->field('a.id, title,c.description')->where(array('reid' => 0,'a.id' => 23))->select();
        $this->data['copyrights'] =$copyrights;
    }

    // 推荐产品
    public function goodProduct()
    {
        $this->getElseData2('goodProduct', 2, 'a.id, title, shorttitle, writer, source, litpic, pubdate, a.description', 'c');
    }


    // 热销产品
    public function hotProduct()
    {
        $this->getElseData2('hotProduct', 2, 'a.id, title, shorttitle, writer, source, litpic, pubdate, a.description', 'h');
    }

    // 最新产品
    public function newProduct()
    {
        $this->getElseData2('newProduct', 2, 'a.id, title, shorttitle, writer, source, litpic, pubdate, a.description', 'f');
    }

    // 最新咨询
    public function newAsk()
    {
        $this->getData('newAsk', 11, 'a.id, title, shorttitle, writer, source, litpic, pubdate, c.description');
    }

    // 新闻列表
    public function newWorks()
    {
        $this->getElseData('newWorks', 3, 'a.id, title, shorttitle, writer, source, litpic, pubdate, a.description', 'a');
        // dump(M()->getLastSQL());
    }

    // 新闻中心
    public function newWork()
    {
        $this->getData('newWork', 13, 'a.id, title, shorttitle, writer, source, litpic, pubdate, c.description,body');
    }

    // banner
    public function banner()
    {
        $this->getData('banner', 18, 'a.id, title, shorttitle, writer, source, litpic, pubdate, c.description');
    }


    public function news()
    {
        $id = I('id');
        $new = M('addonarticle as a')->join('__ARCHIVES__ as b on a.aid = b.id')->where(array('a.aid'=>$id,'b.id'=>$id))->field('title,litpic,pubdate,body')->find();
        $new['pubdate'] = date('Y-m-d', $new['pubdate']);
        $this->data['archives'] = $new;
    }



    // 查询数据方法
    public function getData($typename, $typeid, $keys = '*')
    {
        $this->data[$typename] = M('arctype as a')
            ->join('__ARCHIVES__ as c on a.id = c.typeid')
            ->join('__ADDONARTICLE__ as b on c.id = b.aid')
            ->field($keys)
            ->where(array('a.id' => $typeid, 'a.reid' => 1))
            ->select();
    }

    // 查询数据方法
    public function getElseData2($typename, $typeid, $keys = '*', $icon)
    {
        $keys = $keys.', a.flag';
        $this->data[$typename] = M('archives as a')
            // ->join('__ARCTYPE__ as b on b.id = a.typeid')
            ->join('__CHANPIN__ as c on a.id = c.aid')
            ->field($keys)
            ->where(array('a.typeid' => $typeid))
            ->select();

        $count = count($this->data[$typename]);
        for ($i=0; $i < $count; $i++) {
            $arr = explode(',', $this->data[$typename][$i]['flag']);
            if (!in_array($icon, $arr)) {
                unset($this->data[$typename][$i]);
            }
        }
    }

    // 查询其他数据方法
    public function getElseData($typename, $typeid, $keys = '*', $icon)
    {
        $keys = $keys.', a.flag';
        $this->data[$typename] = M('archives as a')
            // ->join('__ARCTYPE__ as b on b.id = a.typeid')
            ->join('__ADDONARTICLE__ as c on a.id = c.aid')
            ->field($keys)
            ->where(array('a.typeid' => $typeid))
            ->select();

        $count = count($this->data[$typename]);
        for ($i=0; $i < $count; $i++) {
            $arr = explode(',', $this->data[$typename][$i]['flag']);
            if (!in_array($icon, $arr)) {
                unset($this->data[$typename][$i]);
            }
        }
    }

    // 转化数据为json格式
    public function __destruct()
	{
        // dump($this->data);exit;
		echo json_encode($this->data);
	}
}
