<?php
/**
 * NginxServerManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Tools
 *
 * @date    21.07.12
 */

abstract class NginxServerManager extends stdClass
{
	protected $nginxDir;

	protected $serverDir;

	protected $hostFile;

	public function __construct()
	{
		$this->nginxDir = '/usr/local/etc/nginx/sites-enabled';
		$this->serverDir = ROOT;
		$this->hostFile = '/etc/hosts';
	}

	protected function writeToConsole($message, $type = 'success')
	{
		echo $message . PHP_EOL;
	}

	protected function restartNginxServer()
	{
		shell_exec('sudo nginx -s stop');
		shell_exec('sudo nginx');
	}

	protected function fileExist($file)
	{
		return file_exists($file);
	}

	protected function createDirectory($pathname)
	{
		if(!$this->fileExist($pathname)){
			mkdir($pathname);
		}

		shell_exec('sudo chmod -R 777 ' . $pathname	);
	}

}
