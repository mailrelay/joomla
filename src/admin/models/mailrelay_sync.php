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


jimport('joomla.application.component.model');
class MailRelay_SyncModelMailRelay_Sync extends JModel
{

	/**
	* Here we get all users from our Joomla! database
	*/
	function getAllUsers()
	{
		$database =& JFactory::getDbo();
		
		$query = "SELECT * FROM " . $database->nameQuote('#__users') . ";";
		
		$database->setQuery($query);
		
		$result = $database->loadObjectList();
		
		return $result;
	}	
}


?>