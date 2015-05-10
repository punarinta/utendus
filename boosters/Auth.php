<?php

class Auth
{
    const ROLE_GUEST = 0b00;
    const ROLE_USER  = 0b01;
    const ROLE_ADMIN = 0b10;

    /**
     * Checks if user is logged in
     * Is much faster than user()
     *
     * @return mixed
     */
    static function check()
    {
        return isset ($_SESSION['-AUTH']['user']);
    }

    /**
     * Quick access to the current User entity
     *
     * @return bool
     */
    static function user()
    {
        return \Sys::svc('Auth')->user();
    }

    /**
     * Tells if I have a specified role
     *
     * @param $role
     * @return bool
     */
    static function amI($role)
    {
        if (!self::check())
        {
            // is true only if the role is GUEST
            return !$role;
        }

        return $_SESSION['-AUTH']['user']->role && $role;
    }
}