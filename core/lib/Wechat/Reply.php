<?php
namespace core\lib\Wechat;
/**
 * 回复微信信息
 * Class Name
 */
class Reply{
    /**
     * 接收的信息对象
     * @var
     */
    private $data;
    /**
     * 回复的xml
     * @param $xml
     */
    private $xml;

    public function __construct($data)
    {
        $this->data = is_object($data) ? $data : (object)$data;
    }


    /**
     * 回复文本
     * @param $content
     */
    public function text($content)
    {

        $arr = $this->_getBase('text');

        $arr['Content'] = $content;

        $this->xml = self::createXml($arr);

        return $this;
    }

    /**
     * 回复图片消息
     */
    public function image($MediaId)
    {

        $arr = $this->_getBase('image');

        $arr['Image'] = array(
            'MediaId'=>$MediaId
        );

        $this->xml = self::createXml($arr);

        return $this;
    }

    /**
     * 回复语音信息
     * @param $MediaId
     * @return $this
     */
    public function voice($MediaId)
    {
        $arr = $this->_getBase('voice');

        $arr['Voice'] = array(
            'MediaId'=>$MediaId
        );

        $this->xml = self::createXml($arr);

        return $this;
    }

    /**
     * 回复视频消息
     */
    public function video(array $Video)
    {
        $arr = $this->_getBase('video');

        $arr['Video'] = $Video;

        $this->xml = self::createXml($arr);

        return $this;
    }

    /**
     * 回复音乐消息
     */
    public function music(array $Music)
    {
        $arr = $this->_getBase('music');

        $arr['Music'] = $Music;

        $this->xml = self::createXml($arr);

        return $this;
    }

    /**
     * 回复图文消息
     */
    public function news(array $Article)
    {
        $arr = $this->_getBase('news');

        $arr['ArticleCount'] = count($Article);

        $arr['Articles'] = $Article;

        $this->xml = self::createXml($arr);
        //log_message('debug' ,  $this->xml);
        return $this;
    }

    /**
     * 获取接受者openid
     * @return mixed
     */
    public function getToUserName()
    {
        return $this->data->FromUserName;
    }

    /**
     * 获取发送者微信号
     * @return mixed
     */
    public function getFromUserName()
    {
        return $this->data->ToUserName;
    }

    /**
     * 回复时间
     * @return int
     */
    public function getCreateTime()
    {
        return time();
    }


    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public static function createXml($data, $root='xml', $item='item', $attr='', $id='id', $encoding='utf-8')
    {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   = "<{$root}{$attr}>";
        $xml   .= self::data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }


    /**
     * 生成XML
     * @param array $array
     */
    private static function data_to_xml($data)
    {

        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  is_array($val) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }


    public static function xmlSafeStr($str)
    {
        return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';
    }

    /**
     * 获取基础信息
     */
    private function _getBase($type)
    {
        return array(
            'ToUserName'=>$this->getToUserName(),
            'FromUserName'=>$this->getFromUserName(),
            'CreateTime'=>$this->getCreateTime(),
            'MsgType'=>$type
        );
    }

    /**
     * 回复
     */
    public function reply()
    {
        if($this->xml){
            $xml = '';
            if(config('SafeMode') == 2)
            {
                $crypt = new \core\lib\Wechat\Crypt(config('TOKEN') , config('EncodingAesKey') , config('WECHAT_APPID'));
                $errCode = $crypt->encryptMsg($this->xml , time() , uniqid() , $xml);
                if($errCode <> 0)
                {
                    \core\bin\Logger::error('XML加密失败:'.$errCode);
                }
            }else{
                $xml = $this->xml;
            }
            echo $xml;
        }else{
            echo 'fail';
        }
    }


} 