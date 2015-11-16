<?php
namespace core\lib;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/27
 * Time: 23:24
 */
class Session{


    static public $flash_key = 'flash';

    static public $session_id_ttl;

    static private function _ready()
    {
        ini_set('session.gc_maxlifetime', 7200);
        $session_id_ttl = config('SESSION.sess_expiration');
        if (is_numeric($session_id_ttl))
        {
            if ($session_id_ttl > 0)
            {
                self::$session_id_ttl = $session_id_ttl;
            }
            else
            {
                self::$session_id_ttl = (60*60*24*365*2);
            }
        }
        // check if session id needs regeneration
        if (self::_session_id_expired())
        {
            // regenerate session id (session data stays the
            // same, but old session storage is destroyed)
            self::_regenerate_id();
        }
        // delete old flashdata (from last request)
        self::_flashdata_sweep();
        // mark all new flashdata as old (data will be deleted before next request)
        self::_flashdata_mark();
    }


    /**
     * Checks if session has expired
     */
    static private function _session_id_expired()
    {
        if ( ! isset($_SESSION['regenerated']))
        {
            $_SESSION['regenerated'] = time();
            return false;
        }
        $expiry_time = time() - self::$session_id_ttl;
        if ( $_SESSION['regenerated'] <=  $expiry_time )
        {
            return true;
        }
        return false;
    }

    /**
     * PRIVATE: Internal method - removes "flash" session marked as 'old'
     */
    static private function _flashdata_sweep()
    {
        foreach ($_SESSION as $name => $value)
        {
            $parts = explode(':old:', $name);
            if (is_array($parts) && count($parts) == 2 && $parts[0] == self::$flash_key)
            {
                self::del($name);
            }
        }
    }

    /**
     * PRIVATE: Internal method - marks "flash" session attributes as 'old'
     */
    static private function _flashdata_mark()
   {
       foreach ($_SESSION as $name => $value)
       {
           $parts = explode(':new:', $name);
           if (is_array($parts) && count($parts) == 2)
           {
               $new_name = self::$flash_key.':old:'.$parts[1];
               self::set($new_name, $value);
               self::del($name);
           }
       }
   }

    /**
     * Regenerates session id
     */
    static private function _regenerate_id()
    {
        // copy old session data, including its id
        $old_session_id = session_id();
        $old_session_data = $_SESSION;
        // regenerate session id and store it
        session_regenerate_id();
        $new_session_id = session_id();
        // switch to the old session and destroy its storage
        session_id($old_session_id);
        session_destroy();
        // switch back to the new session id and send the cookie
        session_id($new_session_id);
        session_start();
        // restore the old session data into the new session
        $_SESSION = $old_session_data;
        // update the session creation time
        $_SESSION['regenerated'] = time();
        // end the current session and store session data.
        session_write_close();
    }


    /**
     * @param $key
     */
    static public function get($key)
    {
        self::_ready();
        // added for backward-compatibility
        if ($key == 'session_id')
        {
            return session_id();
        }
        else
        {
            return ( ! array_key_exists($key , $_SESSION)) ? false : $_SESSION[$key];
        }
    }

    /**
     * @param $key
     * @param $value
     */
    static public function set($key , $value = '')
    {
        self::_ready();
        if (is_string($key))
        {
            $key = array($key => $value);
        }
        if (count($key) > 0)
        {
            foreach ($key as $k => $val)
            {
                $_SESSION[$k] = $val;
            }
        }
    }

    /**
     * @param $key
     */
    static public function del($key)
    {
        self::_ready();
        if (is_string($key))
        {
            $key = array($key => '');
        }
        if (count($key) > 0)
        {
            foreach ($key as $k => $val)
            {
                unset($_SESSION[$k]);
            }
        }
    }

    /**
     * Sets "flash" data which will be available only in next request (then it will
     * be deleted from session). You can use it to implement "Save succeeded" messages
     * after redirect.
     */
    static public function set_flash($key , $value)
    {
        self::_ready();
        if (is_string($key))
        {
            $key = array($key => $value);
        }
        if (count($key) > 0)
        {
            $flash_data = array();
            foreach ($key as $k => $val)
            {
                $flash_data[self::$flash_key . ':new:' . $k] = $val;
            }
            self::set($flash_data);
        }
    }

    /**
     * Keeps existing "flash" data available to next request.
     */
    static public function keep_flash($key)
    {
        self::_ready();
        $old_flash_key = self::$flash_key.':old:'.$key;
        $value = self::get($old_flash_key);
        $new_flash_key = self::$flash_key.':new:'.$key;
        self::set($new_flash_key, $value);
    }

    /**
     * Returns "flash" data for the given key.
     */
    static public function get_flash($key)
    {
        self::_ready();
        $flash_key = self::$flash_key.':old:'.$key;
        return self::get($flash_key);
    }

}