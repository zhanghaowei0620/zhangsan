<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class WeixinController extends Controller
{
    public function accessToken()
    {
        Cache::pull('access');exit;
        $access = Cache('access');
        if (empty($access)) {
            $appid = "wx51db63563c238547";
            $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appkey";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info, true);
            $key = "access";
            $access = $arrInfo['access_token'];
            $time = $arrInfo['expires_in'];

            cache([$key => $access], $time);
        }

        return $access;
    }

    /**自定义菜单添加*/
    public function createadd(Request $request){
        $access = $this->accessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access";
        $arr = array(
            'button'=>array(
                array(
                    "name"=>"xxx",
                    "type"=>"click",
                    "key"=>"dianji",
                    "sub_button"=>array(
                        array(
                            "type"=>"pic_sysphoto",
                            "name"=>"打开相机",
                            "key"=>"xiangji",
                        ),
                    ),
                ),
                array(
                    "name"=>"链接",
                    "type"=>"view",
                    "url"=>"https://www.baidu.com"
                ),
                array(
                    "name"=>"位置",
                    "type"=>"location_select",
                    "key"=>"weizhi"
                )
            ),
        );

        $strJson = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $objurl = new Client();
        $response = $objurl->request('POST',$url,[
            'body' => $strJson
        ]);
        $res_str = $response->getBody();
        //var_dump($res_str);
        return $res_str;
    }
}
