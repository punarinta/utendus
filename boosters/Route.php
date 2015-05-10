<?php

class Route
{
    /**
     * Generates a URL for an existing route.
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    static function url($name, $params = [])
    {
        $url = $GLOBALS['-R'][$name][0];

        foreach ($params as $k => $v)
        {
            $url = str_replace('{' . $k . '}', $v, $url);
        }

        return $url;
    }
}