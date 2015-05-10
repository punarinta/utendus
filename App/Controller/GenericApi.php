<?php

namespace App\Controller;

class GenericApi
{
    /**
     * API entry point
     *
     * @return mixed
     * @throws \Exception
     */
    static public function index()
    {
        // check if it's a POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            throw new \Exception('HTTP method must be POST.');
        }

        // check if method is provided
        if (!$method = \Input::json('method'))
        {
            throw new \Exception('No payload found or no method specified.');
        }

        // check if this method exists
        if (!method_exists(get_called_class(), $method))
        {
            throw new \Exception('Method \'' . $method . '\' does not exist.');
        }

        // set up language
        if (\Auth::check())
        {
            \Lang::setLocale(\Auth::user()->locale);
        }

        // setup pagination
        \DB::$pageStart  = \Input::json('pageStart');
        \DB::$pageLength = \Input::json('pageLength');

        return forward_static_call([get_called_class(), $method]);
    }
}