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
		$this->nginxDir = '/opt/local/etc/nginx/sites-enabled';
		$this->serverDir = __DIR__ . '/../..';
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

}
