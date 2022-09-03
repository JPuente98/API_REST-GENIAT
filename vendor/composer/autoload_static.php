<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit09ff55affb95e541a2ff45588d4e2127
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit09ff55affb95e541a2ff45588d4e2127::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit09ff55affb95e541a2ff45588d4e2127::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit09ff55affb95e541a2ff45588d4e2127::$classMap;

        }, null, ClassLoader::class);
    }
}