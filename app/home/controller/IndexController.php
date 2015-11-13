<?php
namespace app\home\controller;
use core\bin\WechatController;

defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 23:27
 */

class IndexController extends WechatController{

    protected $openid = 'oFPn7t5sdvcvMexqo4-wjqgoeWMg';

    public function index()
    {
        echo load('Index')->get();
    }

}