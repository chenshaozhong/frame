<?php
namespace core\lib\Wechat;
/**
 * JSSDK签名生成
 * Created by PhpStorm.
 * User: 专属
 * Date: 2015/7/12
 * Time: 23:09
 */
class Jssdk{

    private $appId;
    private $appSecret;
    private $cache;

    public function __construct($appId = null , $secret = null)
    {
        $this->appId = is_null($appId) ? config('WECHAT_APPID') : $appId;
        $this->appSecret = is_null($secret) ? config('WECHAT_SECRET') : $secret;
        $this->cache = new \core\bin\Cache('common');
    }

    /**
     * jssdk配置
     * @return array
     */
    public function jsApi()
    {
        return $this->signature($this->getJsApiTicket());
    }

    /**
     *卡券签名票据
     * @return string
     */
    public function cardApi()
    {
        return $this->signature($this->getWxCardTicket());
    }

    /**
     * 签名
     * @param $Ticket
     */
    private function signature($Ticket)
    {
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        $string = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s" , $Ticket , $nonceStr , $timestamp, $url);
        $signature = sha1($string);
        return array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
        );
    }

    /**
     * 生成随机字符串
     * @param int $length
     * @return string
     */
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * jsapi票据
     * @return mixed
     */
    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $this->cache->get('jsapi_ticket' , true);
        if ($data['expire_time'] < time())
        {
            $accessToken = $this->getAccessToken();
            $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=%s" , $accessToken);
            $res = json_decode(\core\lib\Http::get($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $cache['expire_time']  = time() + 7000;
                $cache['jsapi_ticket'] = $ticket;
                $this->cache->set('jsapi_ticket', $cache, 7000 , true);
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }
        return $ticket;
    }

    /**
     * 卡券票据
     * @return mixed
     */
    public function getWxCardTicket()
    {
        // wx_card_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $this->cache->get('wx_card_ticket' , true);
        if ($data['expire_time'] < time())
        {
            $accessToken = $this->getAccessToken();
            $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token=%s" , $accessToken);
            $res = json_decode(\core\lib\Http::get($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $cache['expire_time']  = time() + 7000;
                $cache['wx_card_ticket'] = $ticket;
                $this->cache->set('wx_card_ticket', $cache, 7000 , true);
            }
        } else {
            $ticket = $data['wx_card_ticket'];
        }
        return $ticket;
    }

    /**
     * 获取token
     * @return mixed
     */
    public function getAccessToken()
    {
        //access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $this->cache->get('access_token' , true);
        if ($data['expire_time'] < time()) {
            //如果是企业号用以下URL获取access_token
            $url = sprintf("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s" , $this->appId , $this->appSecret);
            $res = json_decode(\core\lib\Http::get($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                $this->cache->set('access_token', $data, 7000 , true);
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

}