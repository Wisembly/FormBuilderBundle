<?php

require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

$loader->registerNamespaces(array(
    'Symfony' => __DIR__.'/../vendor/symfony/src',
    'Balloon\\Bundle\\FormBuilderBundle' => __DIR__.'/../../../..',
    'Doctrine\\DBAL' => __DIR__.'/../vendor/dbal/lib',
    'Doctrine\\Common' => __DIR__.'/../vendor/common/lib',
    'Mockery' => '.',
));

$loader->registerPrefixes(array(
    'Twig_'  => __DIR__.'/../vendor/twig/lib',
));

$loader->register();

require_once 'Mockery/Loader.php';
require_once 'Hamcrest/hamcrest.php';
$loader = new \Mockery\Loader;
$loader->register();
