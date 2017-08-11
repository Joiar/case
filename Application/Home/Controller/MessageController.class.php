<?php
namespace Home\Controller;

use Think\Controller;

class MessageController extends Controller
{
    public function getMessage()
    {
        if (IS_POST) {
            $arr = I("post.");
            $arr['addtime'] = time();
            $res = M('diyform2')->add($arr);
            if ($res) {
                $backRes = array(
                    'data' => array(
                        'status' => 1, // 1为成功，0为失败
                        'info' => '留言成功!', // 这里放提示信息
                    )
                );
			} else {
                $backRes = array(
                    'data' => array(
                        'status' => 0, // 1为成功，0为失败
                        'info' => '留言失败!', // 这里放提示信息
                    )
                );
			}
            echo json_encode($backRes);
        }
    }

    // 生成验证码
    public function makeCode()
    {
        $config =    array(
            'fontSize'    =>    20,
            'length'      =>    4,
            'useNoise'    =>    false,
            'useCurve'    =>    false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    // 判断验证码是否正确
    function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
}
