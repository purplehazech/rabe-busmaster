<?php

require_once __DIR__.'/buildstrap.php';

// get defined services
$xml = simplexml_load_file($argv[2]);
foreach ($xml->imports->import AS $import) {
        $dics[(string)$import['resource']] = true;
}

// find all services.xml files
$d = new RecursiveDirectoryIterator($argv[1]);
$i = new RecursiveIteratorIterator($d);
foreach ($i AS $v) {
        if ($v->getFilename() != 'services.xml') {
                continue;
        }
        if (!array_key_exists((string) $v, $dics)) {
                $new = $xml->imports->addChild('import');
                $new->addAttribute('resource', (string)$v);
        } else {
		// all good no need to prune
		unset($dics[(string) $v]);
	}
}
$xml->saveXml($argv[2]);


// @todo document and delete leftovers in $dics from xml
