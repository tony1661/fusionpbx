<?php

/*
  FusionPBX
  Version: MPL 1.1

  The contents of this file are subject to the Mozilla Public License Version
  1.1 (the "License"); you may not use this file except in compliance with
  the License. You may obtain a copy of the License at
  http://www.mozilla.org/MPL/

  Software distributed under the License is distributed on an "AS IS" basis,
  WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
  for the specific language governing rights and limitations under the
  License.

  The Original Code is FusionPBX

  The Initial Developer of the Original Code is
  Mark J Crane <markjcrane@fusionpbx.com>
  Portions created by the Initial Developer are Copyright (C) 2008-2024
  the Initial Developer. All Rights Reserved.

  Contributor(s):
  Mark J Crane <markjcrane@fusionpbx.com>
 */

/**
 * Auto Loader class
 * Searches for project files when a class is required. Debugging mode can be set using:
 * - export DEBUG=1
 *      OR
 * - debug=true is appended to the url
 */
class auto_loader {

	const CLASSES_KEY = 'autoloader_classes';
	const CLASSES_FILE = 'autoloader_cache.php';
	const INTERFACES_KEY = "autoloader_interfaces";
	const INTERFACES_FILE = "autoloader_interface_cache.php";

	private $classes;

	/**
	 * Tracks the APCu extension for caching to RAM drive across requests
	 * @var bool
	 */
	private $apcu_enabled;

	/**
	 * Cache path and file name for classes
	 * @var string
	 */
	private static $classes_file = null;

	/**
	 * Maps interfaces to classes
	 * @var array
	 */
	private $interfaces;

	/**
	 * Cache path and file name for interfaces
	 * @var string
	 */
	private static $interfaces_file = null;

	public function __construct() {

		//set if we can use RAM cache
		$this->apcu_enabled = function_exists('apcu_enabled') && apcu_enabled();

		//set cache location
		self::$classes_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::CLASSES_FILE;
		self::$interfaces_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::INTERFACES_FILE;

		//classes must be loaded before this object is registered
		if (!$this->load_cache()) {
			//cache miss so load them
			$this->reload_classes();
			//update the cache after loading classes array
			$this->update_cache();
		}
		//register this object to load any unknown classes
		spl_autoload_register(array($this, 'loader'));
	}

	/**
	 * Update the auto loader
	 */
	public function update() {
		$this->clear_cache();
		$this->reload_classes();
		$this->update_cache();
	}

	public function update_cache(): bool {
		//guard against writing an empty file
		if (empty($this->classes)) {
			return false;
		}

		//update RAM cache when available
		if ($this->apcu_enabled) {
			$classes_cached = apcu_store(self::CLASSES_KEY, $this->classes);
			$interfaces_cached = apcu_store(self::INTERFACES_KEY, $this->interfaces);
			//do not save to drive when we are using apcu
			if ($classes_cached && $interfaces_cached) return true;
		}

		//export the classes array using PHP engine
		$classes_array = var_export($this->classes, true);

		//put the array in a form that it can be loaded directly to an array
		$class_result = file_put_contents(self::$classes_file, "<?php\n return " . $classes_array . ";\n");
		if ($class_result === false) {
			//file failed to save - send error to syslog when debugging
			$error_array = error_get_last();
			self::log(LOG_WARNING, $error_array['message'] ?? '');
		}

		//export the interfaces array using PHP engine
		$interfaces_array = var_export($this->interfaces, true);

		//put the array in a form that it can be loaded directly to an array
		$interface_result = file_put_contents(self::$interfaces_file, "<?php\n return " . $interfaces_array . ";\n");
		if ($interface_result === false) {
			//file failed to save - send error to syslog when debugging
			$error_array = error_get_last();
			self::log(LOG_WARNING, $error_array['message'] ?? '');
		}

		$result = ($class_result && $interface_result);

		return $result;
	}

	public function load_cache(): bool {
		$this->classes = [];
		$this->interfaces = [];

		//use apcu when available
		if ($this->apcu_enabled && apcu_exists(self::CLASSES_KEY)) {
			$this->classes = apcu_fetch(self::CLASSES_KEY, $classes_cached);
			$this->interfaces = apcu_fetch(self::INTERFACES_KEY, $interfaces_cached);
			//don't use files when we are using apcu caching
			if ($classes_cached && $interfaces_cached) return true;
		}

		//use PHP engine to parse it
		if (file_exists(self::$classes_file)) {
			$this->classes = include self::$classes_file;
		}

		//do the same for interface to class mappings
		if (file_exists(self::$interfaces_file)) {
			$this->interfaces = include self::$interfaces_file;
		}

		//catch edge case of first time using apcu cache
		if ($this->apcu_enabled) {
			apcu_store(self::CLASSES_KEY, $this->classes);
			apcu_store(self::INTERFACES_KEY, $this->interfaces);
		}

		//return true when we have classes and false if the array is still empty
		return (!empty($this->classes) && !empty($this->interfaces));
	}

	public function reload_classes() {
		//set project path using magic dir constant
		$project_path = dirname(__DIR__, 2);

		//build the array of all locations for classes in specific order
		$search_path = [
			$project_path . '/resources/interfaces/*.php',
			$project_path . '/resources/traits/*.php',
			$project_path . '/resources/classes/*.php',
			$project_path . '/*/*/resources/interfaces/*.php',
			$project_path . '/*/*/resources/traits/*.php',
			$project_path . '/*/*/resources/classes/*.php',
			$project_path . '/core/authentication/resources/classes/plugins/*.php',
		];

		//get all php files for each path
		$files = [];
		foreach ($search_path as $path) {
			$files = array_merge($files, glob($path));
		}

		//reset the current array
		$class_list = [];

		//store PHP language declared classes, interfaces, and traits
		$current_classes = get_declared_classes();
		$current_interfaces = get_declared_interfaces();
		$current_traits = get_declared_traits();

		//store the class name (key) and the path (value)
		foreach ($files as $file) {

			//include the new class
			try {
				include_once $file;
			} catch (Exception $e) {
				//report the error
				self::log(LOG_ERR, "Exception while trying to include file '$file': " . $e->getMessage());
				continue;
			}

			//get the new classes
			$new_classes = get_declared_classes();
			$new_interfaces = get_declared_interfaces();
			$new_traits = get_declared_traits();

			//check for a new class
			$classes = array_diff($new_classes, $current_classes);
			if (!empty($classes)) {
				foreach ($classes as $class) {
					$class_list[$class] = $file;
				}
				//overwrite previous array with new values
				$current_classes = $new_classes;
			}

			//check for a new interface
			$interfaces = array_diff($new_interfaces, $current_interfaces);
			if (!empty($interfaces)) {
				foreach ($interfaces as $interface) {
					$class_list[$interface] = $file;
				}
				//overwrite previous array with new values
				$current_interfaces = $new_interfaces;
			}

			//check for a new trait
			$traits = array_diff($new_traits, $current_traits);
			if (!empty($traits)) {
				foreach ($traits as $trait) {
					$class_list[$trait] = $file;
				}
				//overwrite previous array with new values
				$current_traits = $new_traits;
			}
		}

		//add only new classes
		if (!empty($class_list)) {
			foreach ($class_list as $class => $location) {
				if (!isset($this->classes[$class])) {
					$this->classes[$class] = $location;
				}
			}
		}

		//load interface to class mappings
		$this->reload_interface_list();
	}

	/**
	 * The loader is set to private because only the PHP engine should be calling this method
	 * @param string $class_name The class name that needs to be loaded
	 * @return bool True if the class is loaded or false when the class is not found
	 * @access private
	 */
	private function loader($class_name): bool {

		//sanitize the class name
		$class_name = preg_replace('[^a-zA-Z0-9_]', '', $class_name);

		//find the path using the class_name as the key in the classes array
		if (isset($this->classes[$class_name])) {
			//include the class or interface
			include_once $this->classes[$class_name];

			//return boolean
			return true;
		}

		//Smarty has it's own autoloader so reject the request
		if ($class_name === 'Smarty_Autoloader') {
			return false;
		}

		//cache miss
		self::log(LOG_WARNING, "class '$class_name' not found in cache");

		//set project path using magic dir constant
		$project_path = dirname(__DIR__, 2);

		//build the search path array
		$search_path[] = glob($project_path . "/resources/interfaces/" . $class_name . ".php");
		$search_path[] = glob($project_path . "/resources/traits/" . $class_name . ".php");
		$search_path[] = glob($project_path . "/resources/classes/" . $class_name . ".php");
		$search_path[] = glob($project_path . "/*/*/resources/interfaces/" . $class_name . ".php");
		$search_path[] = glob($project_path . "/*/*/resources/traits/" . $class_name . ".php");
		$search_path[] = glob($project_path . "/*/*/resources/classes/" . $class_name . ".php");

		//collapse all entries to only the matched entry
		$matches = array_filter($search_path);
		if (!empty($matches)) {
			$path = array_pop($matches)[0];

			//include the class, interface, or trait
			include_once $path;

			//inject the class in to the array
			$this->classes[$class_name] = $path;

			//update the cache with new classes
			$this->update_cache();

			//return boolean
			return true;
		}

		//send to syslog when debugging
		self::log(LOG_ERR, "class '$class_name' not found name");

		//return boolean
		return false;
	}

	public function reload_interface_list() {
		$this->interfaces = [];
		if (!empty($this->classes)) {
			$classes = array_keys($this->classes);
			foreach ($classes as $class) {
				$reflection = new ReflectionClass($class);
				$interfaces = $reflection->getInterfaces();
				foreach ($interfaces as $interface) {
					$name = $interface->getName();
					if (empty($this->interfaces[$name])) {
						$this->interfaces[$name] = [];
					}
					$this->interfaces[$name][] = $class;
				}
			}
		}
	}

	/**
	 * Returns a list of classes loaded by the auto_loader. If no classes have been loaded an empty array is returned.
	 * @return array List of classes loaded by the auto_loader or empty array
	 */
	public function get_class_list(): array {
		if (!empty($this->classes)) {
			return $this->classes;
		}
		return [];
	}

	/**
	 * Returns a list of classes implementing the interface
	 * @param string $interface_name
	 * @return array
	 */
	public function get_interface_list(string $interface_name): array {
		//make sure we can return values
		if (empty($this->classes) || empty($this->interfaces)) {
			return [];
		}
		//check if we have an interface with that name
		if (!empty($this->interfaces[$interface_name])) {
			//return the list of classes associated with that interface
			return $this->interfaces[$interface_name];
		}
		//interface is not implemented by any classes
		return [];
	}

	public function get_interfaces(): array {
		if (!empty($this->interfaces)) {
			return $this->interfaces;
		}
		return [];
	}

	public static function clear_cache(string $classes_file = '', string $interfaces_file = '') {

		//check for apcu cache
		if (function_exists('apcu_enabled') && apcu_enabled()) {
			apcu_delete(self::CLASSES_KEY);
			apcu_delete(self::INTERFACES_KEY);
		}

		//set default file
		if (empty(self::$classes_file)) {
			self::$classes_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::CLASSES_FILE;
		}

		//set file to clear
		if (empty($classes_file)) {
			$classes_file = self::$classes_file;
		}

		//remove the file when it exists
		if (file_exists($classes_file)) {
			@unlink($classes_file);
			$error_array = error_get_last();
			//send to syslog when debugging with either environment variable or debug in the url
			self::log(LOG_WARNING, $error_array['message'] ?? '');
		}

		if (empty(self::$interfaces_file)) {
			self::$interfaces_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::INTERFACES_FILE;
		}

		//set interfaces file to clear
		if (empty($interfaces_file)) {
			$interfaces_file = self::$interfaces_file;
		}

		//remove the file when it exists
		if (file_exists($interfaces_file)) {
			@unlink($interfaces_file);
			$error_array = error_get_last();
			//send to syslog when debugging with either environment variable or debug in the url
			self::log(LOG_WARNING, $error_array['message'] ?? '');
		}
	}

	private static function log(int $level, string $message): void {
		if (filter_var($_REQUEST['debug'] ?? false, FILTER_VALIDATE_BOOL) || filter_var(getenv('DEBUG') ?? false, FILTER_VALIDATE_BOOL)) {
			openlog("PHP", LOG_PID | LOG_PERROR, LOG_LOCAL0);
			syslog($level, "[auto_loader] " . $message);
			closelog();
		}
	}
}
