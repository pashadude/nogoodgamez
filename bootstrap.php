<?php
$loader = require realpath(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';


use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

/*
if ( ! file_exists($file = realpath(__DIR__) . DIRECTORY_SEPARATOR .'vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run this script.');
}

$loader = require_once $file;*/


$loader->add('Documents', __DIR__);
//var_dump($loader);


$connection = new Connection();

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir(__DIR__ . '/Hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setDefaultDB('nogoodgames');
$config->setMetadataDriverImpl(AnnotationDriver::create(realpath(__DIR__) . DIRECTORY_SEPARATOR .'app/model'));

AnnotationDriver::registerAnnotationClasses();

$dm = DocumentManager::create($connection, $config);

$Game = new Game();
$Game->setName('GTA');
$Game->addGenre('3D');
$dm->persist($Game);
$dm->flush();


?>
