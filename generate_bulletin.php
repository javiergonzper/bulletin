#!/usr/bin/php -q

<?php

if (!isset($argv[1]) || !isset($argv[2])) {
    echo "Usage:\n\tgenerate_bulletin.php <SOURCE_FOLDER> <CREATE_THUMBNAILS true|false>".PHP_EOL;
    die();
}

include_once './lib/bulletin.php';

try{
    $bulletin = new \SG\bulletin\lib\Bulletin($argv[1], $argv[2]);
}catch (\Exception $e){
    echo "Exception".$e->getMessage().PHP_EOL;
}

$output = $bulletin->getHTML();

file_put_contents ($argv[1]."index.html", $output);

echo "Done".PHP_EOL;
//TODO: Render HTML
//TODO: Create ZIP with content
//TODO: Generate PDF
