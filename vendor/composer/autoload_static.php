<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3356859b1b5367a03055b06a5740623b
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit3356859b1b5367a03055b06a5740623b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3356859b1b5367a03055b06a5740623b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
