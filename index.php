<?php
    session_start();
    
    require "vendor/autoload.php";

    use Core\Core;
    use Dotenv\Dotenv;
    use Config\Config;
    
    $dotenv = Dotenv::create(__DIR__);
    $dotenv->load();
    
    $config = new Config();
    define('BASE_URL', $config->getCurrentBaseUrl());

    $core = new Core();
    $core->run();