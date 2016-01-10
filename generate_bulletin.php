#!/usr/bin/php -q

<?php

if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3])) {
    echo "Usage:\n\tgenerate_bulletin.php <SOURCE_FOLDER> <PUBLIC_URL> <CREATE_THUMBNAILS true|false>".PHP_EOL;
    die();
}

include_once './lib/bulletin.php';

try{
    $bulletin = new \SG\bulletin\lib\Bulletin($argv[1], $argv[2], $argv[3]);
}catch (\Exception $e){
    echo "Exception".$e->getMessage().PHP_EOL;
}

file_put_contents ($argv[1]."index.json", json_encode($bulletin->getBulletin()));
file_put_contents ($argv[1]."index.html", $bulletin->getHTML());

echo "Done".PHP_EOL;
//TODO: Render HTML
//TODO: Create ZIP with content
//TODO: Generate PDF
