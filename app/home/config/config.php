<?php
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 23:04
 */
return [

    'CACHE'=>[
        'default' =>[
            'CACHE_TYPE' => 'FileCache',
            'CACHE_PATH' => APP_PATH . 'cache'.DIRECTORY_SEPARATOR,
            'GROUP' => 'runtime',
            'HASH_DEEP' => 0,
        ],
        'redis' =>[
            'CACHE_TYPE' => 'Redis',
            'SERVER' => '127.0.0.1',
            'PORT' => 6379,
            'GROUP' => 'onRedis'
        ],
        'apc' =>[
            'CACHE_TYPE' => 'Apc',
            'GROUP' => 'onApc'
        ],
    ],

    'WECHAT_APPID'=>'wx9467b53e5422b8dd',//微信appId
    'WECHAT_SECRET'=>'d9e676c8edf8f9cc01d63c49784f6f73',//微信secret
    'TOKEN'=>'kissaoli',//微信token
    'EncodingAesKey'=>'1222222',//微信secret
    'SafeMode'=>1,//模式（1:明文模式,2：安全模式）

    /**
     * 普通消息类型
     * 类型=>服务
     */
    'message'=>[
        'text'=>'text',
        'image'=>'image',
        'voice'=>'voice',
        'shortvideo'=>'shortvideo',
        'location'=>'location',
        'link'=>'link',
    ],

    /**
     * 事件类型
     * 类型=>服务
     */
    'event'=>[
        'subscribe'=>'subscribe',
        'unsubscribe'=>'unsubscribe',
        'scan'=>'scan',
        'location'=>'location',
        'click'=>'click',
        'view'=>'view',
        'card_pass_check'=>'card_pass_check',
        'card_not_pass_check'=>'card_not_pass_check',
        'user_get_card'=>'user_get_card',
        'user_del_card'=>'user_del_card',
        'user_consume_card'=>'user_consume_card',
        'user_view_card'=>'user_view_card',
        'user_enter_session_from_card'=>'user_enter_session_from_card',
        'scancode_push'=>'scancode_push',//扫码推事件的事件推送
        'scancode_waitmsg'=>'scancode_waitmsg',//扫码推事件且弹出“消息接收中”提示框的事件推送
        'pic_sysphoto'=>'pic_sysphoto',
        'pic_photo_or_album'=>'pic_photo_or_album',
        'pic_weixin'=>'pic_weixin',
        'location_select'=>'location_select',//弹出地理位置选择器的事件推送
        'shakearoundusershake'=>'shakearoundusershake',
        'wificonnected'=>'wificonnected',
        'user_scan_product'=>'user_scan_product',
    ],


];