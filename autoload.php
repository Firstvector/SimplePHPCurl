<?php

spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);

    if ('SimplePHPCurl' === array_shift($parts)) {
        $file = sprintf('%s/src/%s.php', __DIR__, implode('/', $parts));

        if (file_exists($file)) {
            require $file;
        }
    }
});
