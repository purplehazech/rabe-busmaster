<?php
/**
 *
 */

$moduledir = $argv[1];
$targetfile = $argv[2];

require_once __DIR__.'/buildstrap.php';

$files = $map = array();

define('AUTOLOADER_TEMPLATE', <<<EOD
<?php

\$map = array();
%LOADERMAP%
EOD
);

// find php files
$d = new RecursiveDirectoryIterator($moduledir);
$i = new RecursiveIteratorIterator($d);
foreach ($i AS $v) {
	$n = $v->getFilename();
	if (substr($n, -4) == '.php' && substr($n, 0, 9) != 'Bootstrap') {
		$files[(string)$v] = $v;
	}
}

foreach ($files AS $l => $f) {
	foreach(get_php_classes(file_get_contents($f)) AS $c) {
		// we dont need no steenkin doubles
		if (isset($map[$c])) {
			var_dump($map[$c], $f);
			trigger_error('same classname twice');
			exit(1);
		}
		$map[$c] = $f;
	}
}

$str = PHP_EOL;
foreach ($map AS $c => $f) {
	$str .= sprintf("\$map['%s'] = __DIR__.'%s';".PHP_EOL, $c, $f->getPathname());
}

// save away
file_put_contents($targetfile, str_replace('%LOADERMAP%', $str, AUTOLOADER_TEMPLATE));

