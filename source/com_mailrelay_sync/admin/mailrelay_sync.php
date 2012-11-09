<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('MailrelaySyncHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'mailrelay_sync.php');
 
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance('MailrelaySync');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

