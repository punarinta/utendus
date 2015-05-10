<?php

class View
{
    /**
     * Renders an pHTML file.
     *
     * @param $path
     * @param null $view
     */
    static function html($path, $view = null)
    {
        require_once 'App/views/' . $path . '.phtml';
    }

    /**
     * Renders JSON data.
     *
     * @param $data
     * @param null $callback
     */
    static function json($data, $callback = null)
    {
        if (!$callback)
        {
            echo json_encode($data);
        }
        else
        {
            echo $callback . '(' . json_encode($data) . ');';
        }
    }
}