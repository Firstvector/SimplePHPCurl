<?php
spl_autoload_register(function ($class) {
    if (false === strpos($class, 'SimplePHPCurl')) {
        return;
    }

    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
