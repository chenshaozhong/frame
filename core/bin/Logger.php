<?php
namespace core\bin;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class Logger{
	/**
	 * Path to save log files
	 *
	 * @var string
	 */
	protected static $_log_path;

	/**
	 * File permissions
	 *
	 * @var	int
	 */
	protected static $_file_permissions = 0644;


	/**
	 * Format of timestamp for log files
	 *
	 * @var string
	 */
	protected static $_date_fmt = 'Y-m-d H:i:s';

	/**
	 * Filename extension
	 *
	 * @var	string
	 */
	protected static $_file_ext;

	/**
	 * Whether or not the logger can write to the log files
	 *
	 * @var bool
	 */
	protected static $_enabled = TRUE;


    protected static $log_on = TRUE;

    /**
     * @param $msg
     * @return bool
     */
    public static function error($msg)
    {
        return self::write_log('ERROR' , $msg);
    }

    /**
     * @param $msg
     * @return bool
     */
    public static function debug($msg)
    {
        return self::write_log('DEBUG' , $msg);
    }

    /**
     * @param $msg
     * @return bool
     */
    public static function info($msg)
    {
        return self::write_log('INFO' , $msg);
    }

    /**
     * @param $level
     * @param $msg
     * @return bool
     */
	public static function write_log($level, $msg)
	{

        self::$log_on = config('LOG_ON') ? : false;

        $log_path = config('LOG_PATH');

        self::$_log_path = ($log_path !== '') ? $log_path : APP_PATH.'logs'.DIRECTORY_SEPARATOR;

        self::$_file_ext = 'php';

        file_exists(self::$_log_path) OR mkdir(self::$_log_path, 0755, TRUE);

        if ( ! is_dir(self::$_log_path) OR ! self::is_really_writable(self::$_log_path))
        {
            self::$_enabled = FALSE;
        }


		if (self::$_enabled === FALSE)
		{
			return FALSE;
		}

        if( ! self::$log_on)
        {
            return FALSE;
        }

		$level = strtoupper($level);

		$filepath = self::$_log_path.'log-'.date('Y-m-d').'.'.self::$_file_ext;
		$message = '';

		if ( ! file_exists($filepath))
		{
			$newfile = TRUE;
			$message .= "<?php defined('ENVIRONMENT') OR exit('No direct script access allowed'); ?>\n\n";
		}

		if ( ! $fp = @fopen($filepath, 'ab'))
		{
			return FALSE;
		}

        $date = date(self::$_date_fmt);

		$message .= $level.' - '.$date.' --> '.$msg."\n";

		flock($fp, LOCK_EX);

        $result = 0;

		for ($written = 0, $length = strlen($message); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, substr($message, $written))) === FALSE)
			{
				break;
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === TRUE)
		{
			@chmod($filepath, self::$_file_permissions);
		}

		return is_int($result);
	}


    public static function is_really_writable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode')))
        {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file))
        {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }

}
