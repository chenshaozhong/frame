<?php
namespace core\lib\Wechat;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/11/4
 * Time: 22:12
 */
class Group{

    protected $token;

    const create = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=%s';

    const get = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=%s';

    const getId = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=%s';

    const update = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token=%s';

    const move = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=%s';

    const batchMove = 'https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token=%s';


    public function __construct($token)
    {
        $this->token = $token ;
    }

    /**
     * 创建分组
     * @param $name
     * @return string
     */
    public function create($name)
    {

        $param = array('group'=>array('name'=>$name));

        $json = json_encode($param);

        return Http::post(sprintf(self::create , $this->token) , $json, 10 , '' , true);
    }

    /**
     * 查询所有分组
     * @return string
     */
    public function get()
    {
        return Http::get(sprintf(self::get , $this->token));
    }

    /**
     * 查询用户所在分组
     * @param $openid
     * @return string
     */
    public function getId($openid)
    {
        $param = array('openid'=>$openid);

        $json = json_encode($param);

        return Http::post(sprintf(self::getId , $this->token) , $json, 10 , '' , true);
    }

    /**
     * 修改分组名
     * @param $id
     * @param $modify_name
     * @return string
     */
    public function update($id , $modify_name)
    {
        $param = array('group'=>array('id'=>$id , 'name'=>$modify_name));

        $json = json_encode($param);

        return Http::post(sprintf(self::update , $this->token) , $json, 10 , '' , true);
    }

    /**
     * 移动用户分组
     * @param $openid
     * @param $to_group
     * @return string
     */
    public function move($openid , $to_group)
    {
        $param = array('openid'=>$openid , 'to_group'=>$to_group);

        $json = json_encode($param);

        return Http::post(sprintf(self::move , $this->token) , $json, 10 , '' , true);
    }

    /**
     * 批量移动用户分组
     * @param $openid_list
     * @param $to_group
     * @return string
     */
    public function batchMove($openid_list , $to_group)
    {
        $param = array('openid'=>$openid_list , 'to_group'=>$to_group);

        $json = json_encode($param);

        return Http::post(sprintf(self::batchMove , $this->token) , $json, 10 , '' , true);
    }



}