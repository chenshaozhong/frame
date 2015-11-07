<?php
namespace core\lib\Wechat;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/11/4
 * Time: 22:49
 */
class ScanProduct{

    protected $api = 'https://api.weixin.qq.com/scan/';

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * 设置商家全局信息
     * @param $param
     * @return string
     */
    public function SetMerchantInfo($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'merchantinfo/set' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 获取商家全局信息
     * @return string
     */
    public function GetMerchantInfo()
    {
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'merchantinfo/get' , $this->token);
        return Http::get($url);
    }

    /**
     * 获取商品子级类目
     * @param $param
     * @return string
     */
    public function GetCategory($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'category/getsub' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 创建商品
     * @param $param
     * @return string
     */
    public function CreateProduct($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/create' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 拉取商品信息
     * @param $param
     * @return string
     */
    public function GetProductInfo($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/get' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 清除商品信息
     * @param $param
     * @return string
     */
    public function ClearProductInfo($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/clear' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 批量查询商品信息
     * @param $param
     * @return string
     */
    public function GetProductList($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/getlist' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 更新商品信息
     * @param $param
     * @return string
     */
    public function UpdateProductInfo($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/update' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 变更商品上下架状态
     * @param $param
     * @return string
     */
    public function ModProductStatus($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/modstatus' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 获取商品二维码
     * @param $param
     * @return string
     */
    public function GetQRCode($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'product/getqrcode' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 检查wxticket参数
     * @param $param
     * @return string
     */
    public function CheckScanTicket($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'scanticket/check' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }

    /**
     * 添加开发者白名单
     * @param $param
     * @return string
     */
    public function SetTestWhiteList($param)
    {
        $param = is_array($param) ? json_encode($param) : $param;
        $url = sprintf('%s%s?access_token=%s' , $this->api , 'testwhitelist/set' , $this->token);
        return Http::post($url , $param , 10 , '' , true);
    }



}