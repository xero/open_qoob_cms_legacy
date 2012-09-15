<?php
/**
 * upload manager
 * a custom class to help manage file uploads for the site
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.4
 * @package qoob
 * @subpackage utils
 */
class upload {
	/**
	 * @var array $allowed and array of allowed file mime types
	 */
	private $allowed = array(
						'text/plain',
						'image/jpeg',
						'image/pjpeg',
						'image/jpg',
						'image/x-jps',						
						'image/png',
						'image/tiff',
						'image/x-tiff',
						'image/gif',
						'image/bmp',
						'application/msword',
						'application/x-msword',
						'application/rtf',
						'application/pdf',
						'application/vnd.ms-excel',
						'application/vnd.ms-powerpoint',
						'application/vnd.oasis.opendocument.text',
						'application/vnd.oasis.opendocument.spreadsheet',
						'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'application/vnd.ms-word.document.macroEnabled.12',
						'application/vnd.ms-word.template.macroEnabled.12',
						'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
						'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
						'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
						'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
						'application/vnd.openxmlformats-officedocument.presentationml.presentation',
						'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
						'application/vnd.ms-excel.sheet.macroEnabled.12',
						'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
						'application/vnd.ms-xpsdocument');
	/**
	 * @var string $dir the directory to upload to
	 */
	private $dir = "";
	/**
	 * constructor
	 * set's the default directory
	 */
	public function __construct() {
		$this->setDirectory("root");
	}
	/**
	 * setter method for for the upload directory
	 * the $location variable is always a subfolder
	 * under the QOOB_ROOT/uploads directory, unless
	 * the keyword "root" is passed. then the directory
	 * is set to the root for the uploads folder.
	 * 
	 * @param string $location
	 */
	public function setDirectory($location) {
		if($location == "" || $location == " ") {
			return false;
		} else if($location == "root") {
			$this->dir = QOOB_ROOT.SLASH."style".SLASH."img".SLASH."projects".SLASH; 
		} else {
			$this->dir = QOOB_ROOT.SLASH."style".SLASH."img".SLASH."projects".SLASH.$location.SLASH; 
		}
	}
	/**
	 * getter method for the upload directory
	 * 
	 * @return string
	 */
	public function getDirectory() {
		return $this->dir;
	}
	/**
	 * returns the extention of a given file
	 * 
	 * @param string $name $_FILES["file"]["name"]
	 * @return string
	 */
	public function getExtention($name) {
		return pathinfo($name, PATHINFO_EXTENSION);
	}
	/**
	 * tests if a given file's type is in the allowed list
	 * 
	 * @param string $type $_FILES["file"]["type"]
	 * @return boolean
	 */
	public function testMIME($type) {
		return (in_array($type, $this->allowed)) ? true : false;
	}
	/**
	 * check to see if a file already exists in the upload directory
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function exists($name) {
		return (file_exists($this->dir.$name)) ? true : false;
	}
	/**
	 * set allowed file types
	 * 
	 * @param array $mimes
	 */
	public function setMIMES($mimes) {
		if(is_array($mimes)) {
			$this->allowed = $mimes;
		}
	}
	/**
	 * upload the file
	 *
	 * @param string $tmpfile $_FILES["file"]["tmp_name"]
	 * @param string $cleanfilename
	 */
	public function file($tmpfile, $cleanfilename) {
		move_uploaded_file($tmpfile, $this->dir.$cleanfilename);
	}
	/**
	 * delete a file from the server.
	 * chmod the file to 0666 then unlink it.
	 * this might be dangerious... use with caution!
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public function delete($filename) {
		if($this->exists($filename)) {
			chmod($this->dir.$filename, 0666);
			unlink($this->dir.$filename);
			return true;
		} else {
			return false;
		}
	}
	/**
	 * manually write a file to the server.
	 *
	 * @param string $filename
	 * @param string $data
	 * @return boolean
	 */
	public function writeFile($filename, $data) {
		$fp = fopen($this->dir.$filename, 'w');
		fwrite($fp, $data);
		fclose($fp);
	}
}

?>