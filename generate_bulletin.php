#!/usr/bin/php -q

<?php

if (!isset($argv[1])) {
    echo "Usage:\n\tgenerate_bulletin.php <SOURCE_FOLDER>".PHP_EOL;
    die();
}

include_once './lib/bulletin.php';

try{
	$bulletin = new \SG\bulletin\lib\Bulletin($argv[1]);
}catch (\Exception $e){
	echo "Exception".PHP_EOL;
}

//TODO: Render HTML
//TODO: Create ZIP with content
//TODO: Generate PDF