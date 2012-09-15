<?php
/**
 * controller class
 * this base class has all the necessary loading
 * functions so that controllers can load models,
 * views, and libraries (e.g. utilities).
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.7
 * @package qoob
 * @subpackage core.mvc
 */
class controller {
//_____________________________________________________________________________________________
//                                                                              setup functions	
	/**
	 * controller constructor
	 * the php magic method to be overridden by your custom
	 * controller classes. use the $classes parameter to 
	 * auto-load needed utility classes (e.g. session)
	 *
	 * @param array $classes
	 * @example parent::__construct(array("session" => array("type" => qoob_types::core, "class" => "session", "dir" => "users/")));
	 */
	public function __construct($classes = null) {
		if(is_array($classes)){
			$this->init($classes);
		}
	}
	/**
	 * initilizer function
	 * loops through each of the elements in the $classes array
	 * and registers them in the qoob framework as public functions
	 * in your controller. use them in $this->class->method format.
	 * 
	 * @param array $classes
	 */
	private function init($classes) {
		//load default libraries
		foreach ($classes as $publicVar => $params) {
			$this->$publicVar = registry::register($params["type"], $params["class"], $params["dir"]);
		}
	}
//_____________________________________________________________________________________________
//                                                                             loader functions
	/**
	 * library loader function
	 * used to register classes into the qoob framework as public functions
	 * in your controller. use them in $this->class->method format.
	 *
	 * @param string $type
	 * @param string $class
	 * @param string $path
	 * @param boolean $singleton
	 */
	public function library($type = "", $class = "", $path = "", $singleton = false){
		if($type == "" || $class == "") return false;
		
		$publicVar = $class;
        $this->$publicVar = registry::register($type, $class, $path, $singleton);
	}
	/**
	 * view loader function
	 * used to load a view into the qoob framework. any data in the optional
	 * $data array is extracted and becomes available in the php code in the
	 * view file loaded. in the $string boolean is set to true, the rended
	 * view code is returned from this function. otherwise the view is just
	 * included and immediatly rendered.
	 *
	 * @param string $view
	 * @param array $data
	 * @param boolean $string
	 * @return string
	 */
	public function view($view, $data=array(), $string=false) {
		$file = APP_PATH."/views/".$view.".php";
        if(sizeof($data) > 0)
        extract($data, EXTR_SKIP);
        
        if(file_exists($file)) {
        	if($string) {
        		//pause echoing
        		ob_start();
                //look at code igniter similarity ->> system/libraries/loader.php line 671.
                //echo eval(file_get_contents($file));
                include($file);
				//read file as string to variables
                $content = ob_get_contents();
                //cleanup
                ob_end_clean();
                return $content;
        	} else {
                //just include file.
                include($file);        		
        	}
        } else {
        	throw new Exception("unable to load template: $file", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
        return true;
	}
	/**
	 * model loader function
	 * used to load a database model into the qoob framework.
	 * if the $data array is not null, it will be passed to
	 * the models constructor.
	 *
	 * @param string $model
	 * @param array $data
	 * @return class
	 */
	public function model($model, $data=array()) {
		$file = APP_PATH."/models/".$model.".php";
        
        if(file_exists($file)) {
        	//check if exists in library
        	if(!library::catalog()->$model) {
            	library::catalog()->$model = true;
        		include($file);
        	}
        	//create model
	        if(sizeof($data) > 0) {
	        	$class = new $model($data);
	        } else {
	        	$class = new $model();
	        }
            return $class;
        } else {
            throw new Exception("unable to load model: $file", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
            return false;
        }
	}	
	/**
	 * log message function
	 * writes data to a given log file.
	 *
	 * @param string $msg  the message to save
	 * @param string $file the filename to save to
	 */
	public function logMSG($msg, $file = 'log.txt') {
		$fd = fopen($file, "a");
		$str = $msg."\n";
		fwrite($fd, $str);
		fclose($fd);
		return true;
	}		
}

?>