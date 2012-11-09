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
class MailrelaySyncViewManualsync extends JView
{
	function display ($tpl = null)
	{
                // get the Data
                $form = $this->get('Form');
                $script = $this->get('Script');
		$groups = $this->get('Groups');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign the Data
                $this->form = $form;
                $this->script = $script;
		$this->groups = $groups;

                // Set the toolbar
                $this->addToolBar();

                // Display the template
                parent::display($tpl);

                // Set the document
                $this->setDocument();
	}

        protected function addToolBar() 
        {
                JRequest::setVar('hidemainmenu', true);
                JToolBarHelper::title(JText::_('COM_MAILRELAY_SYNC_STARTSYNC_LABEL'), 'manualsync');
                JToolBarHelper::custom('manualsync.start', 'checkin', 'checkin', JText::_('COM_MAILRELAY_SYNC_STARTSYNC_ACTION'), false, false);
                JToolBarHelper::cancel('manualsync.cancel', 'JTOOLBAR_CLOSE');
        }

        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_MAILRELAY_STARTSYNC_LABEL'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_mailrelay_sync/views/settings/submitbutton.js");
        }

}


?>
