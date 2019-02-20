<?php

namespace Auth;

/**
 * Class Session
 * @package Auth
 */
class Session
{
    /**
     * Model constructor.
     */
    public static function start()
    {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function createSession()
    {
        self::start();
        if(!isset($_SESSION['DATE_CREATED'])) {
            $_SESSION['DATE_CREATED'] = time();
            session_regenerate_id(true);
        }
    }

    /**
     * Validar sessão
     */
    public static function validate()
    {
        if(time() - $_SESSION['DATE_CREATED'] < 1800) {
            session_regenerate_id(true);
        } else {
            ob_end_clean();
            http_response_code(401);
            self::destroy();
            echo 'Sessão inválida';
            die;
        }
    }

    /**
     * @param $name
     */
    public static function unsetParam($name)
    {
        if(isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
        if(isset($_SESSION)) {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
    }

    /**
     * Destruir sessão
     */
    public static function destroy()
    {
        if(session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}