<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * HelloWorld component helper.
 */
abstract class MailrelaySyncHelper
{
        /**
         * Configure the Linkbar.
         */
        public static function addSubmenu($submenu) 
        {
                JSubMenuHelper::addEntry(JText::_('COM_MAILRELAY_SYNC_CONFIG_LABEL'),
                                         'index.php?option=com_mailrelay_sync&view=settings&extension=com_mailrelay_sync', $submenu == 'settings');
                JSubMenuHelper::addEntry(JText::_('COM_MAILRELAY_SYNC_START_LABEL'),
                                         'index.php?option=com_mailrelay_sync&view=manualsync&extension=com_mailrelay_sync',
                                         $submenu == 'sync');
                // set some global property
                $document = JFactory::getDocument();
                $document->addStyleDeclaration('.icon-48-helloworld ' .
                                               '{background-image: url(../media/com_helloworld/images/tux-48x48.png);}');
                if ($submenu == 'categories') 
                {
                        $document->setTitle(JText::_('COM_HELLOWORLD_ADMINISTRATION_CATEGORIES'));
                }
        }

}
