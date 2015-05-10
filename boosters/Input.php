<?php

class Input
{
    /**
     * Safely read HTTP GET received data
     *
     * @param $k
     * @return mixed
     */
    static function get($k)
    {
        return isset ($_GET[$k]) ? $_GET[$k] : null;
    }

    /**
     * Safely read HTTP POST received data
     *
     * @param $k
     * @return mixed
     */
    static function post($k)
    {
        return isset ($_POST[$k]) ? $_POST[$k] : null;
    }

    /**
     * Gets the value of a route element.
     *
     * @param $k
     * @return int
     */
    static function route($k)
    {
        return isset ($GLOBALS['-R-VAR'][$k]) ? $GLOBALS['-R-VAR'][$k] : null;
    }

    /**
     * Gets a JSON variable from a posted HTTP data.
     *
     * @param $k
     * @return null
     */
    static function json($k)
    {
        if (!isset ($GLOBALS['-P-JSON'])) $GLOBALS['-P-JSON'] = json_decode(file_get_contents('php://input'), 1);

        return Sys::aPath($GLOBALS['-P-JSON'], $k);
    }

    /**
     * Tells if this is an AJAX request
     *
     * @return bool
     */
    static function isAjax()
    {
        return isset ($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}