<?php

/**
 * @version             $Id: helloworld.php 51 2010-11-22 01:33:21Z chdemko $
 * @package             Joomla16.Tutorials
 * @subpackage  Components
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author              Christophe Demko
 * @link                http://joomlacode.org/gf/project/helloworld_1_6/
 * @license             License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Settings Model
 */
class mailrelaysyncModelManualsync extends JModelAdmin
{
	protected $option = "com_mailrelay_sync";

        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         * @since       1.6
         */
        public function getTable($type = 'MailRelay', $prefix = 'Table', $config = array()) 
        {
                $table = JTable::getInstance($type, $prefix, $config);
		return $table;
        }
        /**
         * Method to get the record form.
         *
         * @param       array   $data           Data for the form.
         * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
         * @return      mixed   A JForm object on success, false on failure
         * @since       1.6
         */
        public function getForm($data = array(), $loadData = true) 
        {
                // Get the form.
                $form = $this->loadForm('com_mailrelay_sync.manualsync', 'manualsync', array('control' => 'jform', 'load_data' => $loadData));
                if (empty($form)) 
                {
                        return false;
                }
                return $form;
        }
        /**
         * Method to get the script that have to be included on the form
         *
         * @return string       Script files
         */
        public function getScript() 
        {
                return 'administrator/components/com_mailrelay_sync/models/forms/settings.js';
        }
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         * @since       1.6
         */
        protected function loadFormData() 
        {
		$data = JFactory::getApplication()->getUserState('com_mailrelay_sync.edit.manualsync.data', array());

                // Check the session for previously entered form data.
                if (empty($data)) 
                {
                        $data = $this->getItem(1);
                }
                return $data;
        }

	public function verify($host, $user, $password)
	{
		$url = "http://".$host."/ccm/admin/api/version/2/&type=json";
		$curl = curl_init($url);

		$params = array(
			"function"=>"doAuthentication",
			"username"=>$user,
			"password"=>$password
		);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

                $headers = array(
                	'X-Request-Origin' => 'Joomla2.5|1.1|'.JPlatform::getShortVersion() 
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		// call the page, it will return a JSON
		$result = curl_exec($curl);
		if ($result)
		{
			$jsonResult = json_decode($result);
	
			if (!$jsonResult->status)
			{
				// error
				return false;
			}
			else
			{
				// if the user validates correctly, we will get the groups data
				$apiKey = $jsonResult->data;
				$params = array(
					"function"=>"getGroups",
					"apiKey"=>$apiKey
				);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

                        	$headers = array(
                                	'X-Request-Origin' => 'Joomla2.5|1.1|'.JPlatform::getShortVersion() 
                        	);
                        	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($curl);
				$jsonResult = json_decode($result);
	
				if (!$jsonResult->status)
				{
					// error en grupos
					return false;
				}
				else
				{
					return $jsonResult->data;
				}
			}
		}
		else
		{
			return false;
		}
	}
}
