<?php
/**
  * ZipManager
  * 
  * PHP class for creating and downlading zip archives.
  *
  * This content is released under the MIT License (MIT)
  *
  * Copyright (c) 2016 Daniele De Santis
  *
  * Permission is hereby granted, free of charge, to any person obtaining a copy
  * of this software and associated documentation files (the "Software"), to deal
  * in the Software without restriction, including without limitation the rights
  * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  * copies of the Software, and to permit persons to whom the Software is
  * furnished to do so, subject to the following conditions:
  *
  * The above copyright notice and this permission notice shall be included in
  * all copies or substantial portions of the Software.
  *
  * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  * THE SOFTWARE.
  * 
  * @author     Daniele De Santis <hello@danieledesantis.net>
  * @copyright  Copyright (c) 2016, Daniele De Santis (http://www.danieledesantis.net/)
  * @license	http://opensource.org/licenses/MIT	MIT License
  * @link 		https://www.danieledesantis.net
  * @version	1.0.0
  */

class ZipManager {

	private static $_zip_dir;
	private static $_filename;

	/**
	 * @param string $zip_dir
	 */
	public static function set_zip_dir($zip_dir) {
		
		if ( self::check_dir($zip_dir) ) {
			self::$_zip_dir = $zip_dir;
		}
		
	}

	/**
	 * @param string $filename
	 */
	public static function set_filename($filename) {
		
		self::$_filename = self::manage_filename($filename);
		
	}
	
	/**
	 * @param string $filename
	 * @return string
	 */
	private static function manage_filename($filename) {
		
		if( strtolower(substr($filename, -4)) !== '.zip' ) {
			$filename .= '.zip';
		}
		return $filename;
		
	}

	/**
	 * @param string $zip_dir
	 * @return mixed
	 */
	private static function check_dir($zip_dir) {
		
		if( !is_dir($zip_dir) || !is_writable($zip_dir) ) {
			return false;
		}
		return $zip_dir;
		
	}

	/**
	 * @param mixed $files
	 * @return mixed
	 */
	private static function check_files($files) {
		
		$valid_files = array();
		
		if (!$files) {
			return false;
		}

		if ( is_array($files) ) {
			foreach($files as $file) {
				if ( file_exists($file) ) {
					$valid_files[] = $file;
				}
			}
		} else {
			if ( file_exists($files) ) {
				$valid_files[] = $files;
			}
		}
		
		if ( ! count($valid_files) ) {
			return false;
		} else {
			return $valid_files;
		}
		
	}

	/**
	 * @param mixed $files
	 * @param string $filename
	 * @param string $zip_dir
	 * @param bool $download
	 * @return mixed
	 */
	public static function zip_create($files, $filename = null, $zip_dir = null, $overwrite = null, $download = null) {
		
		$valid_files = self::check_files($files);
		$zip_dir = is_null($zip_dir) ? self::$_zip_dir : self::check_dir($zip_dir);
		$filename = is_null($filename) ? self::$_filename : self::manage_filename($filename);

		if ( is_null($overwrite) ) {
			$overwrite = false;
			$zip_open_flag = ZipArchive::CREATE;
		} else {
			$overwrite = true;
			$zip_open_flag = ZipArchive::OVERWRITE;			
		}

		$download = is_null($download) ? false : true;
		$file = $download ? tempnam($zip_dir, 'zip') : $zip_dir . '/' . $filename;

		if ( file_exists($file) && !$overwrite ) {
			return false;
		}
		
		$zip = new ZipArchive();

		if ( $zip->open($file, $zip_open_flag) !== true ) {
			return false;
		}
		
		foreach($valid_files as $valid_file) {	
			$zip->addFromString(basename($valid_file),file_get_contents($valid_file));
		}

		$zip->close();

		if ($download) {
			return array('file' => $file, 'filename' => $filename);
		} else {
			return file_exists($file);
		}

	}

	/**
	 * @param mixed $files
	 * @param string $filename
	 * @param string $zip_dir
	 */
	public static function zip_download($files, $filename = null, $zip_dir = null) {
		
		$result = self::zip_create($files, $filename, $zip_dir, false, true);

		header('Content-disposition: attachment; filename=' . $result['filename']);
		header('Content-type: application/zip');
		
		readfile($result['file']);
		unlink($result['file']);
		clearstatcache();
		
		exit();

	}

}
?>