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
class MailRelay_SyncViewMailRelay_Sync extends JView
{
	function display ($tpl = null)
	{
		JToolBarHelper::title('MailRelay Sync', 'generic.png');
		
		$status = JRequest::getCmd('status', 'ko');
		$tpl = JRequest::getCmd('template', null);
		
		if($tpl == null){
			parent::display(null);	
		}else{
			parent::display($tpl);	
		}
		
	}
}


?>