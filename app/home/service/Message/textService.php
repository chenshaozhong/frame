<?php
namespace app\home\service\message;
use core\bin\Model;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/6
 * Time: 22:59
 */
class textService extends Model{


    public function done($obj)
    {
        $reply = new \core\lib\Wechat\Reply($obj);
        $reply->text($obj->Content)->reply();
    }

}