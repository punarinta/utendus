<?php

namespace App\Service;

use App\Model\Bcrypt;

class Auth
{
    /**
     * Returns a current logged-in user
     *
     * @return null
     */
    public function user()
    {
        if (isset ($_SESSION['-AUTH']['user']) && isset ($_SESSION['-AUTH']['user']->id))
        {
            return $_SESSION['-AUTH']['user'];
        }

        return null;
    }

    /**
     * Resynchronizes session
     *
     * @return bool
     */
    public function sync()
    {
        // some checks
        if (!isset ($_SESSION['-AUTH']['user'])) return false;
        if (!($userId = $_SESSION['-AUTH']['user']['id'])) return false;

        if (!$user = \Sys::svc('User')->findById($userId))
        {
            return false;
        }

        $_SESSION['-AUTH']['user'] = $user;

        return true;
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    public function login($username, $password)
    {
        // we're logging in by an email
        if (!$user = \Sys::svc('User')->findByEmail($username))
        {
            throw new \Exception('Incorrect username or password.');
        }

        // check password
        if (!Bcrypt::verify($password, $user->password))
        {
            throw new \Exception('Incorrect username or password.');
        }

        $_SESSION['-AUTH']['user'] = $user;
    }

    /**
     * Registers a new user
     *
     * @param $email
     * @param $password
     * @param string $locale
     * @param bool $login
     */
    public function register($email, $password, $locale = 'en_US', $login = false)
    {
        $crypt = new Bcrypt;
        $crypt->setCost(\Sys::cfg('sys.password_cost'));

        $user = \Sys::svc('User')->create(array
        (
            'email'     => $email,
            'password'  => $crypt->create($password),
            'locale'    => $locale,
            'role'      => \Auth::ROLE_USER,
        ));

        if ($login)
        {
            $_SESSION['-AUTH']['user'] = $user;
        }
    }

    /**
     * Changes a password for a user
     *
     * @param $userId
     * @param $password
     * @param $passwordVerify
     * @throws \Exception
     */
    public function changePassword($userId, $password, $passwordVerify)
    {
        if ($password !== $passwordVerify)
        {
            throw new \Exception(\Lang::translate('Password verification is wrong.'));
        }

        if (!isset ($password[5]))
        {
            throw new \Exception('Password should be longer than 5 symbols');
        }

        $user = \Sys::svc('User')->findById($userId);

        $crypt = new Bcrypt;
        $crypt->setCost(\Sys::cfg('sys.password_cost'));

        $user['password'] = $crypt->create($password);
        \Sys::svc('User')->update($user);
    }

    /**
     * Logs you out
     */
    public function logout()
    {
        unset ($_SESSION['-AUTH']);
    }

    /**
     * Incarnates you as a user
     *
     * @param $userId
     * @param bool $forever
     * @param bool $skipCheck
     * @throws \Exception
     */
    public function incarnate($userId, $forever = false, $skipCheck = false)
    {
        if (!\Auth::check() && !$skipCheck)
        {
            throw new \Exception('You need to be logged-in to incarnate.');
        }

        if (!$newUser = \Sys::svc('User')->findById($userId))
        {
            throw new \Exception('User not found. ID = ' . $userId);
        }

        if (!$forever)
        {
            $_SESSION['-AUTH']['real-user'] = $_SESSION['-AUTH']['user'];
        }

        $_SESSION['-AUTH']['user'] = $newUser;
    }

    /**
     * Wakes you up from incarnation
     *
     * @throws \Exception
     */
    public function wakeUp()
    {
        if (!isset ($_SESSION['-AUTH']['real-user']))
        {
            throw new \Exception('No real user exist.');
        }

        $_SESSION['-AUTH']['user'] = $_SESSION['-AUTH']['real-user'];

        unset ($_SESSION['-AUTH']['real-user']);
    }

    /**
     * Tells if you can wake up
     *
     * @return bool
     */
    public function canWakeUp()
    {
        return isset ($_SESSION['-AUTH']['real-user']);
    }
}