<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require(__DIR__ . '/../vendor/autoload.php');

$container  = new ContainerBuilder();
$loader     = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
$loader->load('services.yaml');

$requestFactory = $container->get('requestFactory');
$router         = $container->get('routingHandler');
$responseSender = $container->get('responseSender');

$kernel = new \App\Kernel($requestFactory, $router, $responseSender);
$kernel->run();
