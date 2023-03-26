<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit78ebc77d61b06ffaf82c368d438b2b67
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '667aeda72477189d0494fecd327c3641' => __DIR__ . '/..' . '/symfony/var-dumper/Resources/functions/dump.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\VarDumper\\' => 28,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
            'Maris\\JsonAnalyzer\\' => 19,
        ),
        'L' => 
        array (
            'Location\\' => 9,
        ),
        'A' => 
        array (
            'App\\Tests\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\VarDumper\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/var-dumper',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Maris\\JsonAnalyzer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Location\\' => 
        array (
            0 => __DIR__ . '/..' . '/mjaschen/phpgeo/src',
        ),
        'App\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit78ebc77d61b06ffaf82c368d438b2b67::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit78ebc77d61b06ffaf82c368d438b2b67::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit78ebc77d61b06ffaf82c368d438b2b67::$classMap;

        }, null, ClassLoader::class);
    }
}
