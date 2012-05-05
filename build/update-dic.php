<?php

require_once dirname(__FILE__).'/../src/Bootstrap.php';

/**
 * load xml loader and php dumper
 */
require_once 'sfConfig/FileLocatorInterface.php';
require_once 'sfConfig/FileLocator.php';
require_once 'sfConfig/Resource/ResourceInterface.php';
require_once 'sfConfig/Resource/FileResource.php';
require_once 'sfConfig/Loader/LoaderInterface.php';
require_once 'sfConfig/Loader/Loader.php';
require_once 'sfConfig/Loader/FileLoader.php';
require_once 'sfDependencyInjection/Variable.php';
require_once 'sfDependencyInjection/SimpleXMLElement.php';
require_once 'sfDependencyInjection/Loader/FileLoader.php';
require_once 'sfDependencyInjection/Loader/XmlFileLoader.php';
require_once 'sfDependencyInjection/Dumper/DumperInterface.php';
require_once 'sfDependencyInjection/Dumper/Dumper.php';
require_once 'sfDependencyInjection/Dumper/PhpDumper.php';

/**
 * create some tools
 */
$dc = new Symfony\Component\DependencyInjection\ContainerBuilder;
$ld = new Symfony\Component\DependencyInjection\Loader\XmlFileLoader(
    $dc,
    new Symfony\Component\Config\FileLocator(__DIR__.'/../etc')
);
$dp = new Symfony\Component\DependencyInjection\Dumper\PhpDumper($dc);

/*
 * load from xml and recreate php file
 */
$ld->load('services.xml');
file_put_contents(__DIR__.'/../src/Bootstrap.dic.php', $dp->dump());
