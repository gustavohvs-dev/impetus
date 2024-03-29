<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit14b6160a341f263fb7d669a4c07492fb
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit14b6160a341f263fb7d669a4c07492fb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit14b6160a341f263fb7d669a4c07492fb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit14b6160a341f263fb7d669a4c07492fb::$classMap;

        }, null, ClassLoader::class);
    }
}
