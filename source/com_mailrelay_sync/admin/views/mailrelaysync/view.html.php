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

jimport( 'joomla.application.component.view');
class MailrelaySyncViewMailrelaySync extends JView
{
	function display ($tpl = null)
	{
		JToolBarHelper::title('MailRelay Sync', 'generic.png');
		parent::display($tpl);
	}

}


?>
