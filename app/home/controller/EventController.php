<?php
namespace app\home\controller;
use core\bin\Logger;
use core\bin\WechatController;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * 接收事件
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/29
 * Time: 23:41
 */
class EventController extends WechatController{


    protected $msg_signature;

    public function __construct()
    {
        $this->check();
    }

    public function index()
    {
        $msg = '';
        //获取内容
        $xml = trim(file_get_contents('php://input'));//接收数据

        if(config('SafeMode') == 2)
        {
            $this->msg_signature = $this->get('msg_signature');
            //安全模式
            $crypt = \core\lib\Wechat\Crypt(config('TOKEN') , config('EncodingAesKey') , config('WECHAT_APPID'));
            $errCode = $crypt->decryptMsg($this->msg_signature , $this->timestamp , $this->nonce , $xml , $msg);
            if($errCode <> 0)
            {
                Logger::error('XML解密失败:'.$errCode);
                die($errCode);
            }
        }
        else
        {
            //明文模式
            $msg = $xml;
        }

        $obj = json_decode(json_encode(simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA)));

        if('event' == strtolower($obj->MsgType))
        {
            $event = strtolower($obj->Event);
            $events = config('event');
            //事件不在配置中->过滤
            if( ! in_array($event , array_keys($events))){
                die('fail');
            }
            $service = load(sprintf('event/%s',strtolower($events[$event])) , 'service');
        }
        else
        {
            $msg_type = strtolower($obj->MsgType);
            $msg_types = config('message');
            //信息类型不在配置中->过滤
            if( ! in_array($msg_type , array_keys($msg_types))){
                die('fail');
            }
            $service = load(sprintf('message/%s',strtolower($msg_types[$msg_type])) , 'service');
        }
        $service->done($obj);
    }

} 