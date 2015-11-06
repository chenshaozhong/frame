<?php
namespace app\home\controller;
use core\bin\Controller;
use core\bin\Input;

defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 23:35
 */
class ArticleController extends Controller{

    public function index()
    {
        print_r($_POST);
    }

}