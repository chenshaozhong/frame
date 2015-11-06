<?php
/**
 * 微信控制器
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 13:26
 */
namespace core\bin;
use core\bin\Controller;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
class WechatController extends Controller{

    const oauth_code = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';

    const oauth = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    const queryUser = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s';

    const queryBatchUser = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=%s';

    /**
     * 获取 基础支持 access_token
     * @param null $appId
     * @param null $secret
     * @return mixed
     */
    public function getToken($appId = null , $secret = null)
    {
        $JsSdk = new \core\lib\Wechat\Jssdk($appId , $secret);
        return $JsSdk->getAccessToken();
    }

    /**
     * oauth 授权
     * @param string $scope
     * @param string $state
     */
    public function oauth($scope = 'snsapi_base' , $state = 'long_long_ago')
    {

        if( ! array_key_exists('code' , $_GET) OR Input::get('state') <> $state)
        {
            $this->redirect(sprintf(self::oauth_code , config('WECHAT_APPID') , urlencode($this->currentUrl()) , $scope , $state));
            exit(1);
        }

        $response = Http::get(sprintf(self::oauth , config('WECHAT_APPID') , config('WECHAT_SECRET') , trim(Input::get('code'))));

        $response = json_decode($response);

        if(property_exists($response , 'access_token'))
        {
            return $response->openid;
        }

        Logger::error($response->errmsg);

        return false;
    }

    /**
     * 拉取用户信息
     * @param $openid
     * @param null $token
     * @param string $lang
     * @return string
     */
    public function queryUser($openid , $token = null , $lang = 'zh_CN')
    {
        $token = is_null($token) ? $this->getToken() : $token;

        $url = sprintf(self::queryUser , $token , $openid , $lang);

        return Http::get($url);
    }

    /**批量拉取用户信息
     * @param $openid_list
     * @param null $token
     * @param string $lang
     */
    public function queryBatchUser($openid_list , $token = null , $lang = 'zh_CN')
    {
        $token = is_null($token) ? $this->getToken() : $token;

        $url = sprintf(self::queryBatchUser , $token);

        $json = $openid_list;

        if(is_array($openid_list))
        {
            if(array_key_exists('user_list' , $openid_list))
            {
                $json = json_encode($openid_list);
            }else{
                $temp = array();
                foreach($openid_list as $k=>$openid)
                {
                    $temp['user_list'][$k]['openid'] = $openid;
                    $temp['user_list'][$k]['lang'] = $lang;
                }
                $json = json_encode($temp);
            }

        }
        return Http::post($url , $json , 10 , '' , true);
    }


    /**
     * 接收验证
     */
    public function check()
    {
        $echoStr = Input::get('echostr');
        $signature = Input::get('signature');
        $timestamp =  Input::get('timestamp');
        $nonce =  Input::get('nonce');
        //取token
        $token = config('TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        //字典排序
        sort($tmpArr, SORT_STRING);
        //转字符串
        $tmpStr = implode( $tmpArr );
        //sha1加密
        $tmpStr = sha1( $tmpStr );
        //签名验证
        if( $tmpStr <> $signature )
        {
            die('fail');
        }

        if($echoStr)
        {
            die($echoStr);
        }
    }


    /**
     * 获取当前页面访问地址
     * @return string
     */
    public function currentUrl()
    {
        $pageBaseURL = 'http';
        $uri = '';
        if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on")
        {
            $pageBaseURL .= "s";
        }
        $pageBaseURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageBaseURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageBaseURL .= $_SERVER["SERVER_NAME"];
        }
        if (isset($_SERVER['REQUEST_URI']))
        {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            if (isset($_SERVER['argv']))
            {
                $uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
            } else {
                $uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            }
        }
        return $pageBaseURL . $uri;
    }


}