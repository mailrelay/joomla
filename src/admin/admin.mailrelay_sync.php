<?php
/**
* @package Autor
* @author Jose Argudo
* @website www.joseargudo.com
* @email jose@joseargudo.com
* @copyright 
* @license 
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'controller.php');
if($controller = JRequest::getWord('controller'))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path))
	{
		require_once $path;
	}
	else
	{
		$controller = '';
	}
}
$classname	= 'MailRelay_SyncController'.$controller;
$controller = new $classname();
$controller->execute( JRequest::getVar('task', null, 'default', 'cmd') );
$controller->redirect();


?>