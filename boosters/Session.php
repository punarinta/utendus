<?php

class Session
{
    /**
     * Gets a session variable.
     *
     * @param $k
     * @return mixed
     */
    static function get($k)
    {
        return isset ($_SESSION[$k]) ? $_SESSION[$k] : null;
    }

    /**
     * Sets a session variable.
     *
     * @param $k
     * @param $v
     */
    static function set($k, $v)
    {
        $_SESSION[$k] = $v;
    }
}