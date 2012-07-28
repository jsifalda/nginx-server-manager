<?php
/**
 * CreateServer.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Tools
 *
 * @date    21.07.12
 */

class CreateServer extends NginxServerManager
{

	private $name;

	protected $nginxDir;

	protected $serverDir;

	protected $hostFile;

	public function __construct($name)
	{
		parent::__construct();

		$this->name = str_replace('/', '', $name);
	}

	public function setNginxDir($dir)
	{
		$this->nginxDir = (string) $dir;
	}

	public function setServerDir($dir)
	{
		$this->serverDir = (string) $dir;
	}

	private function getContentOfServerConfigFile()
	{
		$path = $this->serverDir;

		return '
			server { ' . PHP_EOL .
			'listen 80; ' . PHP_EOL .
			'listen [::]:80; ' . PHP_EOL .
			'server_name ' . $this->name . '; ' . PHP_EOL .
			PHP_EOL .
			'root ' . $path . '/' . $this->name . '/www/; ' . PHP_EOL .
			PHP_EOL .
			'error_log ' . $path . '/' . $this->name . '/log/server.error_log;' . PHP_EOL .
			'access_log ' . $path . '/' . $this->name . '/log/' . $this->name . '.access_log;' .
			PHP_EOL .
			'include common.conf; ' . PHP_EOL .
			'include php.conf; ' . PHP_EOL .
			'include nette.conf; ' . PHP_EOL .
			'}';
	}

	private function createServerConfigFile()
	{
		$filename = $this->nginxDir . DIRECTORY_SEPARATOR . $this->name . '.conf';

		if(file_put_contents($filename, $this->getContentOfServerConfigFile())){
			$this->writeToConsole('2. Config file was successful created.');
		}else{
			$this->writeToConsole('2. Config file was not successful created.');
		}
	}

	private function addToHostFile()
	{
		$text = PHP_EOL . '127.0.0.1 '. $this->name;

		if(!$this->fileExist($this->hostFile)){
			$this->writeToConsole('Host file does not exist!');
			return null;
		}

		if(file_put_contents($this->hostFile, $text, FILE_APPEND)){
			$this->writeToConsole('3. Host for ' . $this->name . ' was added.');
		}else{
			$this->writeToConsole('3. Host for ' . $this->name . ' was not added.');
		}
	}

	private function createDirForProject()
	{
		$this->writeToConsole('1. Creating projects folder in root directory.');

		$serverFolder = $this->serverDir . DIRECTORY_SEPARATOR . $this->name;
		$this->createDirectory($serverFolder);

		$logDir = $serverFolder . DIRECTORY_SEPARATOR . 'log';
		$this->writeToConsole('		- Creating log dir');
		$this->createDirectory($logDir);

		$wwwDir = $serverFolder . DIRECTORY_SEPARATOR . 'www';
		$this->writeToConsole('		- Creating www dir');
		$this->createDirectory($wwwDir);
	}

	protected function restartNginxServer()
	{
		$this->writeToConsole('4. Restarting nginx server');
		parent::restartNginxServer();
	}

	public function exec()
	{
		if(!$this->nginxDir or !$this->serverDir){
			throw new Exception('Not set nginx or server directory.');

		}elseif(!$this->fileExist($this->nginxDir)){
			throw new Exception("Bad nginx directory path");

		}else{
			$this->createDirForProject();
			$this->createServerConfigFile();
			$this->addToHostFile();
			$this->restartNginxServer();

			$this->writeToConsole('Finish');
		}
	}

}
