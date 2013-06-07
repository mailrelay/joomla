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
class mailrelaysyncModelSettings extends JModelAdmin
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
                $form = $this->loadForm('com_mailrelay_sync.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_mailrelay_sync.edit.settings.data', array());

                // Check the session for previously entered form data.
                if (empty($data))
                {
                        $data = $this->getItem(1);
                }
                return $data;
        }

	// retrieves the url for the API
	public function getApiUrl($host)
	{
		return "http://".$host."/ccm/admin/api/version/2/&type=json";
	}

	public function verify($host, $apiKey)
	{
		$url = $this->getApiUrl($host);
		$curl = curl_init($url);

		$params = array(
			"function" => "getGroups",
			"apiKey" => $apiKey
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $headers = array(
                'X-Request-Origin: Joomla2.5|1.2|'.JPlatform::getShortVersion()
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($curl);
		$jsonResult = json_decode($result);

		if (!$jsonResult->status)
		{
			// error en grupos
			return $jsonResult->error;
			//return false;
		}
		else
		{
			return $jsonResult->data;
		}
	}

	// lists all users from joomla
	protected function getAllUsers()
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__users";
		$db->setQuery($sql);
		$users = $db->loadObjectList();

		return $users;
	}

        /**
        * Now we start the sync process
        */
	public function sync($host, $apiKey, $groups)
	{
		$url = $this->getApiUrl($host);

                $usuarios = $this->getAllUsers();
                $synced_users = 0;

                $curl = curl_init($url);

                //We loop through all users from our Joomla! installation
                foreach($usuarios AS $usuario)
		{
                        // Call getSubscribers
                        $params = array(
                                'function' => 'getSubscribers',
                                'apiKey' => $apiKey,
                                'email' => $usuario->email
                        );

                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

                        $headers = array(
                                'X-Request-Origin: Joomla2.5|1.2|'.JPlatform::getShortVersion()
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                        $result = curl_exec($curl);
                        $jsonResult = json_decode($result);

                        if (!$jsonResult->status) {
                                $usuarios_mailrelay = new StdClass;
                        } else {
                                $data = $jsonResult->data;
                                $usuarios_mailrelay = $data[0];
                        }

                        //We check if the user already exists in the API
                        if($usuarios_mailrelay->email == $usuario->email)
			{
                                // Call updateSubscriber
                                $params = array(
                                        'function' => 'updateSubscriber',
                                        'apiKey' => $apiKey,
                                        'id' => $usuarios_mailrelay->id,
                                        'email' => $usuario->email,
                                        'name' => $usuario->name,
                                        'groups' => $groups
                                );

                                $post = http_build_query($params);

                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_POST, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

	                        $headers = array(
        	                        'X-Request-Origin: Joomla2.5|1.2|'.JPlatform::getShortVersion()
                	        );
                        	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                $result = curl_exec($curl);

                                $jsonResult = json_decode($result);

                                if ($jsonResult->status) {
                                        $synced_users++;
                                }

                        }else{

                                // Call addSubscriber
                                $params = array(
                                        'function' => 'addSubscriber',
                                        'apiKey' => $apiKey,
                                        'email' => $usuario->email,
                                        'name' => $usuario->name,
                                        'groups' => $groups
                                );

                                $post = http_build_query($params);

                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_POST, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

	                        $headers = array(
        	                        'X-Request-Origin: Joomla2.5|1.2|'.JPlatform::getShortVersion()
                	        );
                        	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                $result = curl_exec($curl);

                                $jsonResult = json_decode($result);

                                if ($jsonResult->status) {
                                        $synced_users++;
                                }
                        }
                }
		return $synced_users;

	}
}
