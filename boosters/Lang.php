<?php

class Lang
{
    /**
     * Sets the current locale.
     *
     * @param $locale
     */
    static function setLocale($locale)
    {
        $GLOBALS['-LC'] = $locale;
        putenv("LC_ALL=$locale");
        setlocale(LC_ALL, $locale);
        bindtextdomain('Start', './App/translations');
        textdomain('Start');
    }

    /**
     * Translates a singular form
     *
     * @param $key
     * @return mixed
     */
    static function translate($key)
    {
        return gettext($key);
    }

    /**
     * Translates with a support of plurals
     *
     * @param $a
     * @param $b
     * @param int $x
     * @return mixed
     */
    static function translatePlural($a, $b, $x = 1)
    {
        return ngettext($a, $b, $x);
    }
}