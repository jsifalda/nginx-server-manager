<?php
/**
 * RemoveServer.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Tools
 *
 * @date    21.07.12
 */

class RemoveServer extends NginxServerManager implements IManager
{

	private $name;

	protected $nginxDir;

	protected $serverDir;

	protected $hostFile;

	private $removeWithSource;

	public function __construct($name, $forceRemove = false)
	{
		parent::__construct();

		$this->name = $name;
		$this->removeWithSource = $forceRemove;
	}

	public function setNginxDir($dir)
	{
		$this->nginxDir = (string) $dir;
	}

	public function setServerDir($dir)
	{
		$this->serverDir = (string) $dir;
	}

	public function exec()
	{
		if(!$this->nginxDir or !$this->serverDir){
			throw new Exception('Not set nginx or server directory.');

		}elseif(!$this->fileExist($this->nginxDir)){
			throw new Exception("Bad nginx directory path");

		}else{
			$this->removeConfigFile();
			if($this->removeWithSource) $this->removeSource();
			$this->removeFromHosts();
			$this->restartNginxServer();
		}
	}

	private function removeConfigFile()
	{
		$this->writeToConsole('1. Removing server config file');

		$filename = $this->nginxDir . '/' . $this->name . '.conf';

		if($this->fileExist($filename)){
			return unlink($filename);
//			return shell_exec('sudo rm -rf '.$filename);
		}

		return true;
	}

	private function removeSource()
	{
		$this->writeToConsole('(Removing source code)');

		$path = $this->serverDir . '/' . $this->name;

		if($this->fileExist($path))
		{
//			return unlink($path);
			return shell_exec('sudo rm -rf ' . $path);
		}

		return true;
	}

	private function removeFromHosts()
	{
		$this->writeToConsole('2. Removing from hosts file');

		$text = '127.0.0.1 '. $this->name;

		if(!$this->fileExist($this->hostFile)){
			$this->writeToConsole('Host file does not exist!');
			return null;
		}

		$hostsFileContent = file_get_contents($this->hostFile);
		$newHostsFileContent = str_replace($text, '', $hostsFileContent);

		return file_put_contents($this->hostFile, $newHostsFileContent);
	}

	protected function restartNginxServer()
	{
		$this->writeToConsole('3. Restarting the server.');
		parent::restartNginxServer();
	}
}
