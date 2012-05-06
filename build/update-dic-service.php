<?php

// get defined services
$xml = simplexml_load_file($argv[2]);
foreach ($xml->imports->import AS $import) {
        $dics[(string)$import['resource']] = true;
}

// find all dic.xml files
$d = new RecursiveDirectoryIterator($argv[1]);
$i = new RecursiveIteratorIterator($d);
foreach ($i AS $v) {
        if ($v->getFilename() != 'dic.xml') {
                continue;
        }
        if (!array_key_exists((string) $v, $dics)) {
                $new = $xml->imports->addChild('import');
                $new->addAttribute('resource', (string)$v);
        } else {
		// all good no need to prune
		unset($dics[(string) $vi]);
	}
}
$xml->saveXml($argv[2]);


// @todo document and delete leftovers in $dics from xml
