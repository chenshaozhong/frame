<?php
namespace app\home\controller;
use core\bin\Logger;
use core\bin\WechatController;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/29
 * Time: 23:41
 */
class EventController extends WechatController{


    public function __construct()
    {
        $this->check();
    }


    public function index()
    {
        //获取内容
        $xml = trim(file_get_contents('php://input'));//接收数据

        Logger::debug($xml);

        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $reply = new \core\lib\Wechat\Reply($obj);

        $reply->text($obj->Content)->reply();

    }

} 