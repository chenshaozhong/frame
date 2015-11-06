<?php
namespace core\lib\Wechat;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/11/4
 * Time: 22:48
 */
class MemberCard{

    protected $token;

    const activate = 'https://api.weixin.qq.com/card/membercard/activate?access_token=%s';

    const setFrom = 'https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token=%s';

    const getFrom = 'https://api.weixin.qq.com/card/membercard/userinfo/get?access_token=%s';

    const update = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token=%s';

    public function __construct($token)
    {
        $this->token = $token ;
    }

    /**
     * 激活会员卡
     * @param $data
     * @return string
     */
    public function activate($data)
    {
        if(is_array($data))
        {
            $data = json_encode($data);
        }

        return Http::post(sprintf(self::activate , $this->token) , $data, 10 , '' , true);
    }

    /**
     * 设置开卡字段
     * @param $data
     * @return string
     */
    public function setFrom($data)
    {
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        return Http::post(sprintf(self::setFrom , $this->token) , $data, 10 , '' , true);
    }

    /**
     * 拉取会员信息接口
     * @param $data
     * @return string
     */
    public function getFrom($data)
    {
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        return Http::post(sprintf(self::getFrom , $this->token) , $data, 10 , '' , true);
    }

    /**
     * 更新会员信息
     * @param $data
     * @return string
     */
    public function update($data)
    {
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        return Http::post(sprintf(self::update , $this->token) , $data, 10 , '' , true);
    }

}