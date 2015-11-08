<?php
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 13:26
 */
namespace core\bin;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
class Controller{

    public $vars = [];

    /**
     * @param $name
     * @param $value
     */
    public function assign($name , $value)
    {
        if( is_array($name) )
        {
            foreach($name as $k => $v)
            {
                $this->vars[$k] = $v;
            }
        }else{
            $this->vars[$name] = $value;
        }
    }


    /**
     * @param string $tpl
     * @throws \Exception
     */
    public function display($tpl = '')
    {

        $tpl = $tpl ? : CONTROLLER_NAME.DIRECTORY_SEPARATOR.ACTION_NAME;

        $tpl = str_replace(':', DIRECTORY_SEPARATOR , $tpl);

        $tplFile = config('TPL_PATH') . $tpl . '.php';

        if ( ! file_exists($tplFile) )
        {
            throw new \Exception("Template file '{$tplFile}' not found", 500);
        }

        $template = file_get_contents( $tplFile );

        extract($this->vars, EXTR_OVERWRITE);

        eval('?>' . $template);
    }


    /**
     * @param string $tpl
     * @return string
     * @throws \Exception
     */
    public function render($tpl = '')
    {

        if (ob_get_level())
        {
            ob_end_flush();
            flush();
        }
        ob_start();

        $tpl = $tpl ? : ACTION_NAME;

        $tpl = str_replace(':', DIRECTORY_SEPARATOR , $tpl);

        $tplFile = config('TPL_PATH') . $tpl . '.php';

        if ( ! file_exists($tplFile) )
        {
            throw new \Exception("Template file '{$tplFile}' not found", 500);
        }

        $template = file_get_contents( $tplFile );

        extract($this->vars, EXTR_OVERWRITE);

        eval('?>' . $template);

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }

    /**
     * 页面跳转
     * @param  string  $url  跳转地址
     * @param  integer $code 跳转代码
     * @return void
     */
    public function redirect( $url, $code = 302)
    {
        header('location:' . $url, true, $code);
        exit;
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    public function get($index = NULL , $fitter = NULL)
    {
        return Input::get($index , $fitter);
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    public function post($index = NULL , $fitter = NULL)
    {
        return Input::post($index , $fitter);
    }


}