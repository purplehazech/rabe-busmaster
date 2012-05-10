<?php

$cloverFile = $argv[1];
$srcDir = $argv[2];
$testDir = $argv[3];

$uncovered = $missing = $names = array();

// get completely untested files
$xml = simplexml_load_file($cloverFile);
foreach ($xml->project->file AS $file) {
    if ($file->metrics['coveredelements'] == 0) {
            $file = str_replace($srcDir, '', $file['name']);
            if (substr($file, 0, 9) == 'Bootstrap') {
                    continue;
            }

            $uncovered[] = 'src/'.$file;
            $base = substr($file, 0, -4);
            $arr = explode('/', $base);
            $name =
            $missing[] = 'test/'.$base.'Test.php';
            $names[] = explode("/",$base;
    }
}

for ($i = 0; $i < count($uncovered); $i++) {
    $file = $uncovered[$i];
    $test = $missing[$i];
    $name = $names[$];
    if (file_exists($test)) {
    echo $test;
            continue;
    }

    print $file. ' => ' . $test.PHP_EOL; 

    $phpunitfile = str_replace('test/', 'src/', $test);
    
    echo shell_exec("phpunit --skeleton-test $name $file && mv $phpunitfile $test");
    /*
    phpunit --skeleton-test OscReceive src/modules/oscReceive/Daemon.php | awk '/^Wrote/ {print $6}'
    "/var/lib/jenkins/home/jobs/rabe-busmaster/workspace/src/modules/oscReceive/OscReceiveTest.php".
    */
}

echo "WARNING: This will autocommit & push now!\n";
sleep(3);
echo "This normally runs before version tagging\n";
sleep(5);
echo "Sleeping for a bit...\n";
sleep(7);
echo "And... then I did nothing because this is\n";
echo "rather more work than i though it would\n";
echo "then I hoped i would be and i decided on\n";
echo "focusing on building a robust, per modul\n";
echo "test runner thingy first.";
