<?php

require_once dirname(__FILE__).'/../src/Bootstrap.php';

/**
 * load xml loader and php dumper
 */
require_once 'sfDependencyInjection/Loader/FileLoader.php';
require_once 'sfDependencyInjection/Loader/XmlFileLoader.php';
require_once 'sfDependencyInjection/Dumper/DumperInterface.php';
require_once 'sfDependencyInjection/Dumper/Dumper.php';
require_once 'sfDependencyInjection/Dumper/XmlDumper.php';

/**
 * create some tools
 */
$dc = new Symfony\Component\DependencyInjection\ContainerBuilder;
$ld = new Symfony\Component\DependencyInjection\Loader\XmlFileLoader($dc);
$dp = new Symfony\Component\DependencyInjection\Dumper\PhpDumper($dc);

/*
 * load from xml and recreate php file
 */
$ld->load(dirname(__FILE__).'/../etc/services.xml');
file_put_contents('../src/Bootstrap.dic.php', $dp->dump());
