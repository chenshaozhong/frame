<?php
namespace core\lib\Wechat;
use core\lib\Http;
use core\bin\Logger;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/29
 * Time: 21:23
 */
class Card {

    private  $token;
    /**
     * @var string 卡券类型
     */
    private $card_type;

    /**
     * @var array 卡券基本信息
     */
    private $base_info = array();

    /**
     * @var array 卡券属性
     */
    private $attr = array();

    /**
     * @var string 拉取颜色
     */
    const color = 'https://api.weixin.qq.com/card/getcolors?access_token=%s';
    /*
     * @var string 创建卡券
     */
    const create = 'https://api.weixin.qq.com/card/create?access_token=%s';
    /**
     * @var string 查询code
     */
    const code_get = 'https://api.weixin.qq.com/card/code/get?access_token=%s';
    /**
     * @var string 获取用户已领取卡券接口
     */
    const get_card_list = 'https://api.weixin.qq.com/card/user/getcardlist?access_token=%s';
    /**
     * @var string 查看卡券详情
     */
    const get_card = 'https://api.weixin.qq.com/card/get?access_token=%s';
    /**
     * @var string 批量查询卡券
     */
    const batch_get_card = 'https://api.weixin.qq.com/card/batchget?access_token=%s';
    /**
     * @var string  更新卡券
     */
    const update_card = 'https://api.weixin.qq.com/card/update?access_token=%s';
    /**
     * @var string  修改库存接口
     */
    const modify_stock = 'https://api.weixin.qq.com/card/modifystock?access_token=%s';
    /**
     * @var string 更改code
     */
    const update_code = 'https://api.weixin.qq.com/card/code/update?access_token=%s';
    /**
     * @var string  删除卡券
     */
    const delete_card = 'https://api.weixin.qq.com/card/delete?access_token=%s';
    /**
     * @var string 设置卡券code失效
     */
    const unavailable_code = 'https://api.weixin.qq.com/card/code/unavailable?access_token=%s';
    /**
     * var string 核销code
     */
    const consume_code = 'https://api.weixin.qq.com/card/code/consume?access_token=%s';
    /**
     * var string 解码code
     */
    const decrypt_code = 'https://api.weixin.qq.com/card/code/decrypt?access_token=%s';
    /**
     * var string 二维码领取卡券，获取ticket
     */
    const qrcode_card = 'https://api.weixin.qq.com/card/qrcode/create?access_token=%s';
    /**
     * var string 设置白名单
     */
    const set_whiter = 'https://api.weixin.qq.com/card/testwhitelist/set?access_token=%s';


    public function __construct($access_token)
    {
        $this->token = $access_token;
    }

    /**
     * 获取卡券颜色列表
     */
    public function getColors()
    {
        return Http::get(sprintf(self::color , $this->token));
    }

    /**
     * 创建卡券
     */
    public function create()
    {
        $url = sprintf(self::create , $this->token);
        $post = $this->createArray();

        $json = json_encode($post);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 调用查询code接口可获取code的有效性（非自定义code），该code对应的用户openid、卡券有效期等信息。 自定义code（use_custom_code为true）的卡券调用接口时，post数据中需包含card_id，非自定义code不需上报。
     * @param array $data
     */
    public function codeGet(array $data)
    {
        $url = sprintf(self::code_get , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 获取用户已领取卡券接口
     * @param $data
     */
    public function getCardList(array $data){
        $url = sprintf(self::get_card_list , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 查看卡券详情
     * @param array $data
     */
    public function getCard(array $data)
    {
        $url = sprintf(self::get_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 批量查询卡券
     * @param array $data
     * @return bool
     */
    public function batchGetCard(array $data)
    {
        $url = sprintf(self::batch_get_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 更新卡券信息
     * @param $data
     * @return bool|mixed
     */
    public function updateCard(array $data)
    {
        $url = sprintf(self::update_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 修改库存
     * @param $data
     */
    public function modifyStock(array $data)
    {
        $url = sprintf(self::modify_stock , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 更改code
     * @param $data
     */
    public function updateCode(array $data)
    {
        $url = sprintf(self::update_code , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 删除卡券
     * @param $data
     */
    public function deleteCard(array $data)
    {
        $url = sprintf(self::delete_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 设置code失效
     * @param $data
     * @return bool
     */
    public function unavailableCode(array $data)
    {
        $url = sprintf(self::unavailable_code , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * code核销
     * @param $data
     * @return bool|mixed
     */
    public function consumeCode(array $data)
    {
        $url = sprintf(self::unavailable_code , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 解码code
     * @param $data
     * @return bool
     */
    public function decryptCode(array $data)
    {
        $url = sprintf(self::decrypt_code , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 创建二维码接口
     * @param array $data
     * @return bool|mixed
     */
    public function qrcodeCard(array $data)
    {
        $url = sprintf(self::qrcode_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 设置白名单
     * @param array $data
     * @return bool
     */
    public function setWhiter(array $data)
    {
        $url = sprintf(self::qrcode_card , $this->token);

        $json = json_encode($data);

        return Http::post($url , $json, 10 , '' , true);
    }

    /**
     * 设置卡券类型
     * @param $card_type
     */
    public function setCardType($card_type)
    {
        $this->card_type = strtolower($card_type) ;
    }

    /**
     * 设置基本信息
     * @param $base_info
     */
    public function setBaseInfo(array $base_info)
    {
        foreach($base_info as $key=>$val){
            $this->base_info[$key] = $val;
        }
    }

    /**
     * 设置卡券信息
     * @param array $attr
     */
    public function setAttr(array $attr)
    {
        foreach($attr as $key=>$val){
            $this->attr[$key] = $val;
        }
    }

    /**
     * 创建卡券数组
     */
    private function createArray()
    {
        $card_array = array('card'=>array(
            'card_type'=>strtoupper($this->card_type),
            $this->card_type=>array(
                'base_info'=>$this->base_info,
            ),
        ));
        if($this->attr)
        {
            foreach($this->attr as $key=>$val){
                $card_array['card'][ $this->card_type][$key] = $val;
            }
        }
        return $card_array;
    }





}