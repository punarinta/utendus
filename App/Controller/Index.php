<?php

namespace App\Controller;

class Index
{
    /**
     * This is basically just a placeholder.
     * If you don't use this access point feel free to remove it.
     */
    static function index()
    {
        \View::html('index');
    }

    /**
     * Here you get when trying to access unknown URI.
     *
     * @param $uri
     */
    static function errRoute($uri)
    {
        \View::html('error-404', ['uri' => $uri]);
    }

    /**
     * Here you get when trying to access URI unavailable for your role.
     * If you don't use authentication you may remove these lines.
     *
     * @param $uri
     */
    static function errAccess($uri)
    {
        \View::html('error-401', ['uri' => $uri]);
    }

    /**
     * Here you get when an exception was caught.
     *
     * @param $exception
     */
    static function errRuntime($exception)
    {
        \View::html('error-500', ['e' => $exception]);
    }
}