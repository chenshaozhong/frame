<?php
namespace core\lib\Wechat;
use core\lib\Http;
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/11/3
 * Time: 21:53
 */
class Menu{

    protected $token;

    protected $create = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';

    protected $query = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s';

    protected $delete = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s';

    /**
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token ;
    }

    /**
     * 创建菜单
     * @param $param
     * @return string
     */
    public function create($param)
    {
        $json = is_array($param) ? json_encode($param) : $param;
        return Http::post(sprintf($this->create , $this->token) , $json , 10 , '' , true);
    }

    /**
     * 查询菜单
     * @return string
     */
    public function query()
    {
        return Http::get(sprintf($this->query , $this->token));
    }

    /**
     * 删除菜单
     * @return string
     */
    public function delete()
    {
        return Http::get(sprintf($this->delete , $this->token));
    }


}