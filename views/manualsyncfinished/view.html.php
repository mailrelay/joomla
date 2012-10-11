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
class MailrelaySyncViewManualsyncfinished extends JView
{
	function display ($tpl = null)
	{
		// retrieve number of users synced
		$total = JRequest::getVar("synced");
		$this->total = $total;

		$this->addToolBar();

                // Set the document
                $this->setDocument();

                // Display the template
                parent::display($tpl);

	}

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_MAILRELAY_SYNC_SUCCESS'));
        }

        protected function addToolBar() 
        {
                JRequest::setVar('hidemainmenu', false);
                JToolBarHelper::title(JText::_("COM_MAILRELAY_SYNC_SUCCESS"), 'manualsyncfinished');
        }        
}


?>
