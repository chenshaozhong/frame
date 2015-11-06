<?php
namespace core\lib\Wechat;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/11/1
 * Time: 21:50
 */
class Send{

    protected $token;

    const KFApi = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';

    const TempleIndustry = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=%s';

    const AddTemplate = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=%s';

    const SendTemplate = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';

    /**
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token ;
    }

    /**
     * 发送客服消息
     * @param $openid
     * @param $type
     * @param array $data
     * @param null $customservice
     * @return string
     */
    public function sendKfMessage($openid , $type , $data = array() , $customservice = null)
    {
        $param['touser'] = $openid ;
        $param['msgtype'] = $type ;
        $param[strtolower($type)] = $data ;
        if( ! is_null($customservice))
        {
            $param['customservice'] = array('kf_account'=>$customservice);
        }

        $param = json_encode($param);

        return Http::post(sprintf(self::KFApi , $this->token) , $param , 10 , '' , true);
    }

    /**
     * @param $data
     */
    public function sendTemplateMessage($data)
    {
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        return Http::post(sprintf(self::SendTemplate , $this->token) , $data , 10 , '' , true);
    }

    /**
     * 设置模板消息行业
     * @param array $data
     * @return string
     */
    public function setTemplateIndustry($data = array())
    {
        $param = array();
        if($data)
        {
            foreach($data as $key => $val)
            {
                $param['industry_id'.($key+1)] = $val;
            }

            $param = json_encode($param);
        }

        return Http::post(sprintf(self::TempleIndustry , $this->token) , $param, 10 , '' , true);
    }

    /**
     * 获得模板ID
     * @param $template_id_short
     * @return string
     */
    public function addTemplate($template_id_short)
    {
        $param['template_id_short'] = $template_id_short;
        $param = json_encode($param);
        return Http::post(sprintf(self::AddTemplate , $this->token) , $param, 10 , '' , true);
    }


}