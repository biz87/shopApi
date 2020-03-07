<?php

if (!defined('MODX_CORE_PATH')) {
    $path = dirname(__FILE__);
    while (!file_exists($path . '/mdxcr/config/config.inc.php') && (strlen($path) > 1)) {
        $path = dirname($path);
    }
    define('MODX_CORE_PATH', $path . '/mdxcr/');
}

return [
    'name' => 'shopApi',
    'name_lower' => 'shopapi',
    'version' => '1.0.0',
    'release' => 'pl',
    // Install package to site right after build
    'install' => true,
    // Which elements should be updated on package upgrade
    'update' => [
        'chunks' => false,
        'menus' => false,
        'plugins' => false,
        'resources' => false,
        'settings' => false,
        'snippets' => false,
        'templates' => false,
        'widgets' => false,
    ],
    // Which elements should be static by default
    'static' => [
        'plugins' => false,
        'snippets' => false,
        'chunks' => false,
    ],
    // Log settings
    'log_level' => !empty($_REQUEST['download']) ? 0 : 3,
    'log_target' => php_sapi_name() == 'cli' ? 'ECHO' : 'HTML',
    // Download transport.zip after build
    'download' => !empty($_REQUEST['download']),
];