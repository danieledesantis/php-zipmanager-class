# ZipManager PHP class

Simple PHP class for creating and downlading zip archives easily.
Create and download zip archives with a single line of code. 

## Installation

Include the class in your script:

```php
require_once('path/to/ZipManager.php');
```

## Usage

Create a zip archive from a single file

```php
ZipManager::zip_create('path/to/file.txt', 'archivename', 'destination/folder');
```

Create a zip archive from multiple files

```php
ZipManager::zip_create(array('path/to/file.txt', 'path/to/another/file.png'), 'archivename', 'destination/folder');
```

Create a zip archive and overriding the existing one 

```php
ZipManager::zip_create('path/to/file.txt', 'archivename', 'destination/folder', true);
```

Compress and download a file (the zip archive will not be left on the server)

```php
ZipManager::zip_download(array('path/to/file.txt', 'archivename', 'destination/folder');
```

Set the filename and the destination folder

```php
ZipManager::set_zip_dir('path/to/folder');
ZipManager::set_filename('filename.zip');
ZipManager::zip_create('path/to/file.txt');
ZipManager::zip_create('path/to/file.txt', null, null, true);
ZipManager::zip_download('path/to/file.txt');
ZipManager::zip_download('path/to/file.txt', null, 'path/to/another/folder');
```

Store and print the result

```php
$result = ZipManager::zip_create('path/to/file.txt', 'archivename', 'destination/folder');
echo $result;
```