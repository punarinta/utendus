<?php

namespace App\Controller;

class Index
{
    static function index()
    {
        \View::html('index');
    }
}