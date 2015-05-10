<?php

$config = ['dir' => ['app' => __DIR__ . '/../App']];


if (!file_exists ($config['dir']['app'] . '/routes.php'))
{
    die("No 'routes.php' file found\n\n");
}

$codeRoutes = file_get_contents($config['dir']['app'] . '/routes.php');


// Build routing table

$routes = [];
$controllerNamespace = '\'\\App\\Controller\\';

if (preg_match_all('#Route::set\((.*?)\)#is', $codeRoutes, $routesData, PREG_PATTERN_ORDER))
{
    if (count($routesData) > 1)
    {
        foreach ($routesData[1] as $r)
        {
            $r = explode(',', $r);
            $routes[] = [trim($r[0]), trim($r[1]), trim($r[2]), $controllerNamespace . ltrim($r[3], ' \''), isset($r[4]) ? trim($r[4]) : '\'index\''];
        }
    }
}


// Sort routes in the table

usort($routes, function ($a, $b)
{
    $a = count(explode('/', $a[1]));
    $b = (explode('/', $b[1]));

    if ($a === $b) return 0;
    return $a < $b ? -1 : 1;
});


$codeRoutes = '';

foreach ($routes as $r)
{
    $codeRoutes .= '' . $r[0] . ' => [';
    $codeRoutes .= $r[1] . ', '  . $r[2] . ', ' . $r[3] . ', ' . $r[4];
    $codeRoutes .= "],\n";
}


// Get config file data
$codeConfig = file_get_contents($config['dir']['app'] . '/config.php');
$codeConfig = str_replace("<?php\n", '', $codeConfig);

$pos = strpos($codeConfig, 'return');
if ($pos === false)
{
    die('Config file is malformed.');
}

$codeConfig = substr($codeConfig, $pos + 7, -1);


// Build index.php

$code = file_get_contents(__DIR__ . '/index.php.tpl');

$code = str_replace("/* [INSERT-ROUTES] */", $codeRoutes, $code);
$code = str_replace("/* [INSERT-CONFIG] */", $codeConfig, $code);


// plugin-dependent includes

$plugins = [];

for ($i = 1; $i < count($argv); $i++)
{
    if ($argv[$i] == '-p')
    {
        $i++;
        $plugins = explode(',', $argv[$i]);
    }
}

foreach ($plugins as $plugin)
{
    $code = str_replace('[' . strtoupper($plugin) . '-PLUGIN]', '[-OK]', $code);
}

// pack a bit to prevent undesired editing

// clear unused plugin-related stuff
$code = str_replace('-PLUGIN] */', '-PLUGIN] */ //', $code);

// remove comments
$code = preg_replace('%/\*(?:(?!\*/).)*\*/%s', '', $code);
$code = preg_replace('!//\s.*[\n]!', "\n", $code);
$code = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $code);

// remove endlines
$code = preg_replace('![\n]!', '', $code);

// remove multiple spaces
$code = preg_replace('![\s]{1,}!', ' ', $code);

$sym = function($clear = ' ')
{
    $res = [];
    $sym = ['{','}','(',')','[',']','.',';',',','=','?',':','-'];

    foreach ($sym as $s)
    {
        array_push($res, $clear . $s, $s . $clear);
    }

    return $res;
};

// remove specific spaces
$code = str_replace($sym(), $sym(''), $code);

// add some info
$code = str_replace("<?php", "<?php\n\n// No use to edit this file.\n// Built " . date('d.m.y @ H:i:s O') . "\n\n", $code);


file_put_contents($config['dir']['app'] . '/../public/index.php', $code);

echo "\nFile 'index.php' written to 'public' directory.\n\n";