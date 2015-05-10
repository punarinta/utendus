<?php

class Sys
{
    /**
     * Shortcut call to access app's configuration item
     *
     * @param $k
     * @return null
     */
    static function cfg($k)
    {
        return self::aPath($GLOBALS['-CFG'], $k);
    }
    /**
     * Cached access to services
     *
     * @param $service
     * @return mixed
     */
    static function svc($service)
    {
        if (!isset ($GLOBALS['-SVC'][$service]))
        {
            $class = '\App\Service\\' . $service;
            $GLOBALS['-SVC'][$service] = new $class;
        }
        return $GLOBALS['-SVC'][$service];
    }

    /**
     * Provides an APath access to the array element.
     *
     * @param $a
     * @param null $k
     * @return null
     */
    static function aPath($a, $k = null)
    {
        // return full object
        if ($k === null) return $a;

        // I forgot what
        if (empty ($a)) return null;

        $k = [0, $k];

        while (1)
        {
            $k = explode('.', $k[1], 2);

            if (isset ($a[$k[0]])) $a = $a[$k[0]];
            else return null;

            if (count($k) === 1) break;
        }

        return $a;
    }
}