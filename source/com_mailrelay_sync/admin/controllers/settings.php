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
class MailRelaySyncControllerSettings extends JControllerForm
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
        public function save($key = null, $urlVar = null)
        {
                // Check for request forgeries.
                JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

                // Initialise variables.
                $app   = JFactory::getApplication();
                $lang  = JFactory::getLanguage();
                $model = $this->getModel();
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

                if (!$this->allowSave($data, $key))
                {
                        $this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
                        $this->setMessage($this->getError(), 'error');

                        $this->setRedirect(
                                JRoute::_(
                                        'index.php?option=' . $this->option . '&view=' . $this->view_list
                                        . $this->getRedirectToListAppend(), false
                                )
                        );

                        return false;
                }

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
		if (!isset($validData["automatically_sync_user"]))
		{
			$validData["automatically_sync_user"] = 0;
		}
		if (!isset($validData["automatically_unsync_user"]))
		{
			$validData["automatically_unsync_user"] = 0;
		}

                if (!$model->save($validData))
                {
                        // Save the data in the session.
                        $app->setUserState($context . '.data', $validData);

                        // Redirect back to the edit screen.
                        $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
                        $this->setMessage($this->getError(), 'error');

                        $this->setRedirect(
                                JRoute::_(
                                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
                                )
                        );

                        return false;
                }

                // Save succeeded, so check-in the record.
                if ($checkin && $model->checkin($validData[$key]) === false)
                {
                        // Save the data in the session.
                        $app->setUserState($context . '.data', $validData);

                        // Check-in failed, so go back to the record and display a notice.
                        $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
                        $this->setMessage($this->getError(), 'error');

                        $this->setRedirect(
                                JRoute::_(
                                        'index.php?option=' . $this->option . '&view=' . $this->view_item
                                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
                                )
                        );

                        return false;
                }

                $this->setMessage(
                        JText::_(
                                ($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
                                        ? $this->text_prefix
                                        : 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
                        )
                );

                // Set the record data in the session.
                $recordId = $model->getState($this->context . '.id');
                $this->holdEditId($context, $recordId);
                $app->setUserState($context . '.data', null);
                $model->checkout($recordId);

                // Redirect back to the main screen
                $this->setRedirect(
	                JRoute::_('index.php?option=com_mailrelay_sync'));

                // Invoke the postSave method to allow for the child class to access the model.
                $this->postSaveHook($model, $validData);

                return true;
	}

	public function cancel()
	{
                // Redirect back to the main screen.
                $this->setRedirect(
	                JRoute::_('index.php?option=com_mailrelay_sync'));

	}
}

