<?php
require_once('../ZipManager.php');
// create a zip package named "myfilename.zip" containing "test.txt" inside "mydirectory"
ZipManager::set_zip_dir('mydirectory');
ZipManager::set_filename('myfilename.zip');

$result = ZipManager::zip_create('files/test.txt', null, null, true);
echo $result;

// create a zip package named "mynewfilename.zip" containing "test.txt and test.png" inside "mynewdirectory", then download it and remove it from the folder
ZipManager::set_filename('mynewfilename');

$result = ZipManager::zip_download(array('files/test.txt', 'files/test.png'), null, 'mynewdirectory');
?>