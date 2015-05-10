<?php

// 0. Debugging info

/* [DEBUG-PLUGIN] */ $GLOBALS['t1'] = microtime(1);
/* [DEBUG-PLUGIN] */ $GLOBALS['m1'] = memory_get_usage();

/*
    extract as:

    echo 'Execution time: ' . number_format((microtime(1) - $t1) * 1000, 2) . " ms\n<br>\n";
    echo 'Memory used: ' . number_format((memory_get_usage() - $m1) / 1024, 2) . " kB\n<br>\n";
 */


// 1. Preliminary settings

chdir('..');
date_default_timezone_set('Europe/Stockholm');


// 2. Load namespaces

$manualClasses =
[
    /* [INSERT-PSR] */
];
$autoClasses =
[
    'App\\' => '.',
    /* [INSERT-PSR] */
];

spl_autoload_register(function ($class) use ($manualClasses, $autoClasses)
{
    // class map lookup
    if (isset ($manualClasses[$class]))
    {
        if ($manualClasses[$class])
        {
            include_once $manualClasses[$class];
        }
        else
        {
            return false;
        }
    }

    if (strpos($class, '\\') === false && file_exists('vendor/punarinta/utendus/boosters/' . $class . '.php'))
    {
        include_once 'vendor/punarinta/utendus/boosters/' . $class . '.php';
        return true;
    }

    foreach ($autoClasses as $namespace => $dir)
    {
        if (0 === strpos($class, $namespace))
        {
            if (strpos($class, '_'))
            {
                $class = strtr(strtr($class, $namespace . '_', ''), '_', '/');
            }
            include_once $dir . '/' . strtr($class, '\\', '/') . '.php';
            return true;
        }
    }

    // Remember that this class does not exist.
    return $manualClasses[$class] = false;

}, true, true);


// 3. Cleanup

unset ($manualClasses);
unset ($autoClasses);


// 4. Load configs

$GLOBALS['-CFG'] = /* [INSERT-CONFIG] */
$GLOBALS['-R'] = [/* [INSERT-ROUTES] */];


// 5. Run application

/* [DB-PLUGIN] */ \DB::connect();
/* [AUTH-PLUGIN] */ @session_start();

$uri = rtrim($_SERVER['REQUEST_URI'], '\\');

// go through set up routes
foreach ($GLOBALS['-R'] as $v)
{
    // quick match
    if ($v[0] === $uri)
    {
        /* [AUTH-PLUGIN] */ if (!\Auth::amI($v[1])) return http_response_code(401);

        return call_user_func([$v[2], $v[3]]);
    }

    // parametrized match
    $regex = preg_replace_callback('#(\{[A-Za-z0-9_]+\})#', function ($d)
    {
        // extract names of the route variables
        $GLOBALS['-R-VAR'][str_replace(['{','}'], ['',''], $d[0])] = null;
        return '(.*)';
    }, $v[0]);

    if (strpos($regex, '(') === false) continue;

    if (preg_match('#' . $regex . '#', $uri, $d))
    {
        while (($vv = next($d)) !== false)
        {
            $GLOBALS['-R-VAR'][key($GLOBALS['-R-VAR'])] = $vv;
            next($GLOBALS['-R-VAR']);
        }

        /* [AUTH-PLUGIN] */ if (!\Auth::amI($v[1])) return http_response_code(401);

        return call_user_func([$v[2], $v[3]]);
    }
}

return http_response_code(404);
