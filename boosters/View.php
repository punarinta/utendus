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
     * @param array $data
     */
    static function json($data)
    {
        // TODO: support JSONP
        echo json_encode($data);
    }
}