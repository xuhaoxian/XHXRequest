<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3a2c122f4e580ca2872932ae8d08bf60
{
    public static $prefixLengthsPsr4 = array (
        'X' => 
        array (
            'XHXRequest\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'XHXRequest\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3a2c122f4e580ca2872932ae8d08bf60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3a2c122f4e580ca2872932ae8d08bf60::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
