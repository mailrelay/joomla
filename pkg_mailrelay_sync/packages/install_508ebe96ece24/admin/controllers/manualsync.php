<?php

/**
 * @version             $Id: helloworld.php 48 2010-11-21 20:37:56Z chdemko $
 * @package             Joomla16.Tutorials
 * @subpackage  Components
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author              Christophe Demko
 * @link                http://joomlacode.org/gf/project/helloworld_1_6/
 * @license             License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HelloWorld Controller
 */
class MailRelaySyncControllerManualsync extends JControllerForm
{
	protected $option = "com_mailrelay_sync";

        /**
         * Method to save a record.
         *
         * @param   string  $key     The name of the primary key of the URL variable.
         * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
         *
         * @return  boolean  True if successful, false otherwise.
         *
         * @since   11.1
         */
        public function start($key = null, $urlVar = null)
        {
                // Check for request forgeries.
                JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                // Initialise variables.
                $app   = JFactory::getApplication();
                $lang  = JFactory::getLanguage();
                $model = $this->getModel("settings");
                $table = $model->getTable();
                $data  = JRequest::getVar('jform', array(), 'post', 'array');
                $checkin = property_exists($table, 'checked_out');
                $context = "$this->option.edit.$this->context";
                $task = $this->getTask();

                // Determine the name of the primary key for the data.
                if (empty($key))
                {
                        $key = $table->getKeyName();
                }
		$recordId = 1;	// valor fijo

                // Populate the row id from the session.
                $data[$key] = $recordId;

                // Validate the posted data.
                // Sometimes the form needs some posted data, such as for plugins and modules.
                $form = $model->getForm($data, false);

                if (!$form)
                {
                        $app->enqueueMessage($model->getError(), 'error');

                        return false;
                }

                // Test whether the data is valid.
                $validData = $model->validate($form, $data);

                // Check for validation errors.
                if ($validData === false)
                {
                        // Get the validation messages.
                        $errors = $model->getErrors();

                        // Push up to three validation messages out to the user.
                        for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
                        {
                                if ($errors[$i] instanceof Exception)
                                {
                                        $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                                }
                                else
                                {
                                        $app->enqueueMessage($errors[$i], 'warning');
                                }
                        }

                        // Save the data in the session.
                        $app->setUserState($context . '.data', $data);

                        // Redirect back to the edit screen.
                        $this->setRedirect(
                                JRoute::_(
                                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
                                )
                        );

                        return false;
                }

                // default id
		$validData["id"]=1;

		// implode selected groups
		$validData["groups"] = implode(",", $validData["groups"]);

		// start with sync
                $number_synced = $model->sync($validData["host"], $validData["user"], $validData["password"], $validData["groups"]);

                //Now that the sync process has ended, we redirect to the show process
                $this->setRedirect('index.php?option=com_mailrelay_sync&view=manualsyncfinished&extension=com_mailrelay_sync&synced=' . $number_synced);
                $this->redirect();

                return true;
	}

	public function cancel()
	{
                // Redirect back to the main screen.
                $this->setRedirect(
	                JRoute::_('index.php?option=com_mailrelay_sync'));

	}
}

