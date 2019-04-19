<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**登录页面 */
    public function login(){
        $url = urlencode("http://www.zhanghaowei_blog.com/logintoken");
        $appid = "wx51db63563c238547";
        $scope = "snsapi_userinfo";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";


        return view('index.login',['url'=>$url]);
    }
    //微信登陆
    public function logintoken(Request $request){
        $arr = $request->input();
        $code = $arr['code'];
        $appid = "wx51db63563c238547";
        $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appkey&code=$code&grant_type=authorization_code";
        $info = file_get_contents($accessToken);
        $arr = json_decode($info,true);
        $openid = $arr['openid'];
        $res = DB::table('user')->where('openid',$openid)->first();
        if(empty($res)){
            $openid = [
                'openid'=>$openid,
            ];
            return view('index.login.logintokenlist',['openid'=>$openid]);
        }else{
            $user_id = $res->user_id;
            session(['user_id'=>$user_id]);
            $data = DB::table('goods')->where('goods_recommend',1)->limit(2)->get(['goods_id','goods_name','goods_img','goods_selfprice']);

            //return view('index.index',['data'=>$data]);
            //print_r($res);
        }
    }
    /**执行登录 */
    public function loginDo(Request $request){
        $data=$request->input();
        $user_tel=$request->input('user_tel');
        $user_pwd=$request->input('user_pwd');
        $user_pwd=md5($user_pwd);
        $where=[
            'user_tel'=>$user_tel,
            'user_pwd'=>$user_pwd
        ];
        $date=DB::table('user')->where($where)->first();
        //print_r($date);exit;
        $user_id=$date->user_id;
        // echo $user_id;exit;
        if(!empty($date)){
            session(['user_id'=>$user_id]);
            //echo json_encode(['font'=>'登录成功']);exit;
//            $user_id=$request->session()->get('user_id');
//            //print_r($user_id);exit;
//            $where = [
//                'user_id'=>$user_id
//            ];
//            $arr = DB::table('user')->where($where)->first();
//            $openid = $arr->openid;
//            //print_r($arr->openid);exit;
//            $objurl = new \curl();
//            $accessToken = $this->accessToken();
//            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accessToken";
//
//            $url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openid&lang=zh_CN";
//            $bol2 = $objurl->sendGet($url2);
//            $strjson = json_decode($bol2,JSON_UNESCAPED_UNICODE);
//            $nickname = $strjson['nickname'];
//            $arr = array(
//                'touser'=>$openid,
//                'template_id'=>"skIxpTGsa97WKN_OjbBWpG6ViwkWujElgYlJ6NFwk18",
//                'data'=>array(
//                    'name'=>array(
//                        'value'=>"欢迎".$nickname."登陆",
//                    ),
//                ),
//            );
//            $strjson = json_encode($arr,JSON_UNESCAPED_UNICODE);
//            $bol = $objurl->sendPost($url,$strjson);
            return ['font'=>'登录成功'];
        }else{
            echo json_encode(['font'=>'手机号或密码错误']);exit;
        }

    }

}
