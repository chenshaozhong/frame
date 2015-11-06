<?php
namespace core\lib\Wechat;
use core\lib\Http;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/30
 * Time: 22:13
 */
class Upload{
    /**
     * @var string
     */
    protected $type = 'image';

    protected $token;

    protected $temporaryUrl = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';

    protected $foreverImageUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=%s';

    protected $foreverMediaUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=%s';

    /**
     * @param $token
     * @param string $type
     */
    public function __construct($token , $type = 'image')
    {
        $this->type = $type ;
        $this->token = $token ;
    }

    /**
     * 上传临时素材
     * @param $file文件
     * @param $fromData
     * @return string
    );
     */
    public function temporaryMedia($file)
    {
        $data= array("media"=>curl_file_create($file));
        return Http::post(sprintf($this->temporaryUrl , $this->token , $this->type) , $data , 30 , '' , true);
    }


    /**
     * 上传永久图片
     * @param $file
     * @return string
     */
    public function foreverImage($file)
    {
        $data= array("media"=>curl_file_create($file));
        return Http::post(sprintf($this->foreverImageUrl , $this->token) , $data , 30 , '' , true);
    }

    /**
     * 上传其他永久素材
     * @param $file
     * @param array $description
     * @return string
     */
    public function foreverMedia($file , $description = array())
    {
        $data =  $description ?
        array("media"=>curl_file_create($file) , 'description'=>$description) : array("media"=>curl_file_create($file));
        return Http::post(sprintf($this->foreverMediaUrl , $this->token) , $data , 30 , '' , true);
    }


}