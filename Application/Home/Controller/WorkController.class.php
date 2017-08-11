<?php
namespace Home\Controller;

use Think\Controller;

class WorkController extends Controller
{
    protected $data = array();

    public function index()
    {
        $this->product();
        $this->imgs();
        $this->arctype();
        $this->arctypes();
        $this->copyrights();

    }

    // 版权信息
    public function copyrights()
    {
        $copyrights = M('arctype as a')->join('__ARCHIVES__ as c on a.id = c.typeid')->field('a.id, title,c.description')->where(array('reid' => 0,'a.id' => 23))->select();
        $this->data['copyrights'] =$copyrights;
    }

    public function product()
    {
        $this->getData('product', 16, 'a.id, title, shorttitle, writer, source, litpic, pubdate, a.description, body');
        // $product['body'] = strip_tags($product['body']);
    }

    public function imgs()
    {
        $this->getData('imgs', 17, 'a.id, title, shorttitle, writer, source, litpic');
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
    
    // 查询数据方法
    public function getData($typename, $typeid, $keys)
    {
        $keys ? $keys : '*';
        $this->data[$typename] = M('archives as a')
            ->join('__ARCTYPE__ as b on b.id = a.typeid')
            ->join('__ADDONARTICLE__ as c on a.id = c.aid')
            ->field($keys)
            ->where(array('b.id' => $typeid))
            ->select();
    }

    function sp_getcontent_imgs($content){
        import("Org.phpQuery.phpQuery");
        \phpQuery::newDocumentHTML($content);
        $pq=pq();
        $imgs=$pq->find("img");
        $imgs_data=array();
        if($imgs->length()){
            foreach ($imgs as $img){
                $img=pq($img);
                $im['src']=$img->attr("src");
                // $im['title']=$img->attr("title");
                // $im['alt']=$img->attr("alt");
                $imgs_data[]=$im;
            }
        }
        \phpQuery::$documents=null;
        return $imgs_data;
    }

    // 转化数据为json格式
    public function __destruct()
    {
        // dump($this->data);exit;
        echo json_encode($this->data);
    }
}
