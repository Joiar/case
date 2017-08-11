<?php 



 /**
 *获取html文本里的img
 * @param string $content
 * @return array
 */
function getattr($content){
    import("Org.phpQuery.phpQuery");
    \phpQuery::newDocumentHTML($content);
      $pq=pq($content);
      return $pq->document->documentElement;
     // $str = $pq->document->textContent;
     // $pq=()
     // $pq->find('')
     // $str = $str->document;
      \phpQuery::$documents=null;
    return $str;
}


/**
 *获取html文本里的img
 * @param string $content
 * @return array
 */
function sp_getcontent_imgs($content){
    import("Org.phpQuery.phpQuery");
    \phpQuery::newDocumentHTML($content);
     $pq=pq();
    $imgs=$pq->find("img");
    $imgs_data=array();
    if($imgs->length()){
        foreach ($imgs as $img){
            return $img=pq($img);
            $im['src']=$img->attr("src");
            // $im['title']=$img->attr("title");
            // $im['alt']=$img->attr("alt");
            $imgs_data[]=$im;
        }
    }
    \phpQuery::$documents=null;
    return $imgs_data;
}



