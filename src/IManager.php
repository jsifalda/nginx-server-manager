<?php
/**
 * IManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Tools
 *
 * @date    21.07.12
 */

interface IManager
{

	public function exec();

	public function setNginxDir($dir);

	public function setServerDir($dir);

}
