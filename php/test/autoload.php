<?php
spl_autoload_register(function ($className) {
    // Define the base directory for your project
    $baseDir = __DIR__;

    // Map the namespace prefix to the corresponding base directory
    $namespaceMap = [
        'MuhammadSabri1306\\MyBotLogger'       => $baseDir . '/../',
        'MuhammadSabri1306\\MyBotLogger\\Entities' => $baseDir . '/../Entities/'
    ];

    // Exclude the Helper namespace
    // if(strpos($className, 'App\\Helpers\\') === 0 || strpos($className, 'App\\Tests\\') === 0) {
    //     return;
    // }

    // Loop through the namespace map and check if the class belongs to it
    foreach ($namespaceMap as $namespacePrefix => $baseDirectory) {
        if (strpos($className, $namespacePrefix) === 0) {
            $relativeClass = substr($className, strlen($namespacePrefix));
            $classFile = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($classFile)) {
                require_once $classFile;
            }
            break;
        }
    }
});