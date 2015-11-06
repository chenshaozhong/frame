<?php
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 10:38
 */
define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH.'core'.DIRECTORY_SEPARATOR);
define('APP_NAME', 'home');
define('APP_PATH', ROOT_PATH.'app'.DIRECTORY_SEPARATOR.APP_NAME.DIRECTORY_SEPARATOR);
define("ENVIRONMENT" , 'development');// development / production
require(CORE_PATH.'core.php');