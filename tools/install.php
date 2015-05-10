<?php

// 0. Setup

$root = '../../../';
chdir(dirname(__DIR__));

// 1. Check App directory

echo "Checking 'App' directory in the project root...";
if (!file_exists($root . 'App'))
{
    mkdir($root . 'App');
    echo " Created\n";
}
else echo " OK\n";


// 2. Copy tools

echo "Installing tools...";
if (!file_exists($root . 'tools'))
{
    mkdir($root . 'tools');
}
copy('tools/compile.php.dist', $root . 'tools/compile.php');
echo " OK\n";


// 3. Copy configurable files

echo "Copying configurable files...";
if (!file_exists($root . 'public'))
{
    mkdir($root . 'public');
}
// no problem to overwrite it, it's compiled anyways
copy('public/index.php', $root . 'public/index.php');

if (!file_exists($root . 'App/translations'))
{
    mkdir($root . 'App/translations');
    copy('App/translations/en_US.po', $root . 'App/translations/en_US.po');
}

if (!file_exists($root . 'App/config.php'))
{
    copy('App/config.dist.php', $root . 'App/config.php');
}

if (!file_exists($root . 'App/routes.php'))
{
    copy('App/routes.php', $root . 'App/routes.php');
}

if (!file_exists($root . 'App/views'))
{
    mkdir($root . 'App/views');
    copy('App/views/index.phtml', $root . 'App/views/index.phtml');
}


echo " OK\n";


// 4. Copy source files

echo "Copying source files...";
if (!file_exists($root . 'App/Controller'))
{
    mkdir($root . 'App/Controller');
    copy('App/Controller/Index.php', $root . 'App/Controller/Index.php');
}
if (!file_exists($root . 'App/Service'))
{
    mkdir($root . 'App/Service');
}
if (!file_exists($root . 'App/Model'))
{
    mkdir($root . 'App/Model');
}

foreach (glob("App/Controller/*.php") as $file)
{
    if ($file === 'App/Controller/Index.php') continue;
    copy($file, str_replace('App/Controller', $root . 'App/Controller', $file));
}
foreach (glob("App/Service/*.php") as $file)
{
    copy($file, str_replace('App/Service', $root . 'App/Service', $file));
}
foreach (glob("App/Model/*.php") as $file)
{
    copy($file, str_replace('App/Model', $root . 'App/Model', $file));
}

echo " OK\n";


// 5. Compile app
echo "Compiling application...";
exec('php ' . $root . 'tools/compile.php');

echo " OK\n\nPerfect. Now you can run 'php tools/compile.php' next time you want to recompile it.\n";

echo "\n\n";