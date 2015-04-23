<?php
	

class Ftp
{
	/**#@+ FTP constant alias */
	const ASCII = FTP_ASCII;
	const TEXT = FTP_TEXT;
	const BINARY = FTP_BINARY;
	const IMAGE = FTP_IMAGE;
	const TIMEOUT_SEC = FTP_TIMEOUT_SEC;
	const AUTOSEEK = FTP_AUTOSEEK;
	const AUTORESUME = FTP_AUTORESUME;
	const FAILED = FTP_FAILED;
	const FINISHED = FTP_FINISHED;
	const MOREDATA = FTP_MOREDATA;
	/**#@-*/

	private static $aliases = array(
		'sslconnect' => 'ssl_connect',
		'getoption' => 'get_option',
		'setoption' => 'set_option',
		'nbcontinue' => 'nb_continue',
		'nbfget' => 'nb_fget',
		'nbfput' => 'nb_fput',
		'nbget' => 'nb_get',
		'nbput' => 'nb_put',
	);

	/** @var resource */
	private $resource;

	/** @var array */
	private $state;

	/** @var string */
	private $errorMsg;
	
	private $IS_CONNECTED 	= FALSE;
	private $IS_TRANSFER_COMPLETE = FALSE;


	/**
	 * @param  string  URL ftp://...
	 */
	public function __construct($url = NULL)
	{
		if (!extension_loaded('ftp')) {
			throw new /*\*/Exception("PHP extension FTP is not loaded.");
		}
		if ($url) {
			$parts = parse_url($url);
			$this->connect($parts['host'], empty($parts['port']) ? NULL : (int) $parts['port']);
			$this->login($parts['user'], $parts['pass']);
			$this->pasv(TRUE);
			if (isset($parts['path'])) {
				$this->chdir($parts['path']);
			}
		}
	}



	/**
	 * Magic method (do not call directly).
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws Exception
	 * @throws FtpException
	 */
	public function __call($name, $args)
	{
		$name = strtolower($name);
		$silent = strncmp($name, 'try', 3) === 0;
		$func = $silent ? substr($name, 3) : $name;
		$func = 'ftp_' . (isset(self::$aliases[$func]) ? self::$aliases[$func] : $func);

		if (!function_exists($func)) {
			throw new Exception("Call to undefined method Ftp::$name().");
		}

		$this->errorMsg = NULL;
		set_error_handler(array($this, '_errorHandler'));

		if ($func === 'ftp_connect' || $func === 'ftp_ssl_connect') {
			$this->state = array($name => $args);
			$this->resource = call_user_func_array($func, $args);
			$res = NULL;

		} elseif (!is_resource($this->resource)) {
			restore_error_handler();
			throw new FtpException("Not connected to FTP server. Call connect() or ssl_connect() first.");

		} else {
			if ($func === 'ftp_login' || $func === 'ftp_pasv') {
				$this->state[$name] = $args;
			}

			array_unshift($args, $this->resource);
			$res = call_user_func_array($func, $args);

			if ($func === 'ftp_chdir' || $func === 'ftp_cdup') {
				$this->state['chdir'] = array(ftp_pwd($this->resource));
			}
		}

		restore_error_handler();
		if (!$silent && $this->errorMsg !== NULL) {
			if (ini_get('html_errors')) {
				$this->errorMsg = html_entity_decode(strip_tags($this->errorMsg));
			}

			if (($a = strpos($this->errorMsg, ': ')) !== FALSE) {
				$this->errorMsg = substr($this->errorMsg, $a + 2);
			}

			throw new FtpException($this->errorMsg);
		}

		return $res;
	}



	/**
	 * Internal error handler. Do not call directly.
	 */
	public function _errorHandler($code, $message)
	{
		$this->errorMsg = $message;
	}



	/**
	 * Reconnects to FTP server.
	 * @return void
	 */
	public function reconnect()
	{
		@ftp_close($this->resource); // intentionally @
		foreach ($this->state as $name => $args) {
			call_user_func_array(array($this, $name), $args);
		}
	}



	/**
	 * Checks if file or directory exists.
	 * @param  string
	 * @return bool
	 */
	public function fileExists($file)
	{
		return is_array($this->nlist($file));
	}



	/**
	 * Checks if directory exists.
	 * @param  string
	 * @return bool
	 */
	public function isDir($dir)
	{
		$current = $this->pwd();
		try {
			$this->chdir($dir);
		} catch (FtpException $e) {
		}
		$this->chdir($current);
		return empty($e);
	}



	/**
	 * Recursive creates directories.
	 * @param  string
	 * @return void
	 */
	public function mkDirRecursive($dir)
	{
		$parts = explode('/', $dir);
		$path = '';
		while (!empty($parts)) {
			$path .= array_shift($parts);
			try {
				if ($path !== '') $this->mkdir($path);
			} catch (FtpException $e) {
				if (!$this->isDir($path)) {
					throw new FtpException("Cannot create directory '$path'.");
				}
			}
			$path .= '/';
		}
	}



	/**
	 * Recursive deletes path.
	 * @param  string
	 * @return void
	 */
	public function deleteRecursive($path)
	{
		if (!$this->tryDelete($path)) {
			foreach ((array) $this->nlist($path) as $file) {
				if ($file !== '.' && $file !== '..') {
					$this->deleteRecursive(strpos($file, '/') === FALSE ? "$path/$file" : $file);
				}
			}
			$this->rmdir($path);
		}
	}

}



class FTPCon {
	var $host = '';
	var $username = '';
	var $password = '';
	var $port = 21;
	var $ftpcon = null;
	
	function __construct($host, $username, $password, $port = 21) {
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->port = $port;
	}
	
	function connect() {
		if (isset($this->host, $this->username, $this->password, $this->port)) {
			$this->ftpcon = ftp_connect($this->host, $this->port);
			if (!$this->ftpcon) {
				return false;
			}
			if (!ftp_login($this->ftpcon, $this->username, $this->password)) {
				return false;
			}
			ftp_pasv ($this->ftpcon, true);
			return true;
		}
	}
	
	function ls($dir = '.') {
		if (isset($this->ftpcon)) {
			$ls = ftp_nlist($this->ftpcon, $dir);
			return $ls;
		}
	}
	
	function upload($file, $path, $mode = FTP_ASCII) {
		if (isset($file, $path)) {
			if (file_exists($file)) {
				$upload = ftp_put($this->ftpcon, $path, $file, FTP_ASCII);
				return $upload;
			}
		}
		return false;
	}
	
	function download($file, $path, $mode = FTP_BINARY) {
		if (isset($file)) {
			if (ftp_get($this->ftpcon, $path, $file, FTP_BINARY)) {
				return true;
			}
		}
		return false;
	}
	
	function chmod($file, $permissions = 0644) {
		if (isset($file)) {
			if (ftp_chmod($this->ftpcon, $permissions, $file) !== false) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	function mkdir($dirname) {
		if (isset($dirname)) {
			return ftp_mkdir($this->ftpcon, $dirname);
		}
	}
	
	function delete($file) {
		if (isset($file)) {
			return ftp_delete($this->ftpcon, $file);
		}
	}

	function logout() {
		if (isset($this->ftpcon)) {
			ftp_close($this->ftpcon);
		}
	}
		
	function __destruct() {
		$this->logout();
	}
}

/*
	Usage
	$ftp = new FTPCon('127.0.0.1', 'user', 'password');
if ($ftp->connect()) {
   $dirlist = $ftp->ls();  //Returns an array of files/folders
   $ftp->logout();
}

if ($ftp->connect()) {
    $upload = $ftp->upload('localfile.txt', 'remotefile.txt'); //Returns true/false 
    $ftp->logout();
}

if ($ftp->connect()) {
    $download = $ftp->download('remotefile.txt', 'localfile.txt'); //Returns true/false
    $ftp->logout();
}

if ($ftp->connect()) {
   $dir = $ftp->mkdir('test');  //Return true/false
   $ftp->logout();
}
if ($ftp->connect()) {
   $dir = $ftp->chmod('testfolder', 0644); //Returns true/false
   $ftp->logout();
}
*/

class FtpException extends Exception
{
}

