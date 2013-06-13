<?php
/**
 *
 * Mailrelay
 * A tool to automatically sync users with Mailrelay
 * @copyright	Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Class for Mailrelay
 * @package		Mailrelay
 * @subpackage	mailrelay
 * @version		1.6
 */
class plgUserMailrelay extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	protected function loadSettings($model)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mailrelay_sync'.DS.'tables');
		$table = $model->getTable();
		$table->load(1);

		// get data
		$data["id"] = $table->id;
		$data["autosync"] = $table->automatically_sync_user;
		$data["autounsync"] = $table->automatically_unsync_user;
		$data["host"] = trim($table->host);
		$data["apiKey"] = trim($table->apiKey);
		$data["groups"] = $table->groups;

		return $data;
	}

	function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if(!$success) {
			return false; // if the user wasn't stored we don't resync
		}

		if(!$isnew) {
			return false; // if the user isn't new we don't sync
		}


		// load settings
		if (!class_exists('mailrelaysyncModelSettings'))
		{
			$path =  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mailrelay_sync'.DS.'models';
			JLoader::import("settings", $path);
		}
		$model = JModel::getInstance("Settings", "mailrelaysyncModel");
		$data = $this->loadSettings($model);

		if ($data["autosync"])
		{
			// do the sync
			$apiKey = $data["apiKey"];
			$url = $model->getApiUrl($data["host"]);
			$groups = explode(",", $data["groups"]);
			$curl = curl_init($url);

			$user_id = (int)$user['id'];

			if (empty($user_id)) {
				die('invalid userid');
				return false; // if the user id appears invalid then bail out just in case
			}

			// generate object
			$usuario = new StdClass();
			$usuario->email = $user["email"];
			$usuario->name = $user["name"];

			// call getSubscribers
			$params = array(
				"function"=>"getSubscribers",
				"apiKey"=>$apiKey,
				"email"=>$usuario->email
			);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, 3);

                        $headers = array(
                                'X-Request-Origin: Joomla2.5|1.2|'.JPlatform::getShortVersion()
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($curl);
			$jsonResult = json_decode($result);

			if (!$jsonResult->status)
			{
				$usuarios_mailrelay = new StdClass;
			}
			else
			{
				$data = $jsonResult->data;
				if (count($data)>0)
				{
					$usuarios_mailrelay = $data[0];
				}
				else
				{
					$usuarios_mailrelay = new StdClass;
				}
			}

			if ($usuarios_mailrelay->email == $usuario->email)
			{
				// call updateSubscriber
				$params = array(
					"function"=>"updateSubscriber",
					"apiKey"=>$apiKey,
					"id"=>$usuarios_mailrelay->id,
					"email"=>$usuario->email,
					"name"=>$usuario->name,
					"groups"=>$groups
				);
				$post = http_build_query($params);

				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($curl);
				$jsonResult = json_decode($result);
			}
			else
			{
				// call addSubscriber
				$params = array(
					"function"=>"addSubscriber",
					"apiKey"=>$apiKey,
					"id"=>$usuarios_mailrelay->id,
					"email"=>$usuario->email,
					"name"=>$usuario->name,
					"groups"=>$groups
				);
				$post = http_build_query($params);

				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($curl);
				$jsonResult = json_decode($result);

			}
		}
	}

	function onUserAfterDelete($user, $success, $msg)
	{
		if(!$success) {
			return false; // if the user wasn't stored we don't resync
		}

		// load settings
		if (!class_exists('mailrelaysyncModelSettings'))
		{
			JLoader::import("settings", JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mailrelay_sync'.DS.'models');
		}
		$model = JModel::getInstance("Settings", "mailrelaysyncModel");
		$data = $this->loadSettings($model);

		if ($data["autounsync"])
		{
			// do the unsync
			$apiKey = $data["apiKey"];
			$url = $model->getApiUrl($data["host"]);
			$groups = explode(",", $data["groups"]);
			$curl = curl_init($url);

			$user_id = (int)$user['id'];

			if (empty($user_id)) {
				die('invalid userid');
				return false; // if the user id appears invalid then bail out just in case
			}

			// generate object
			$usuario = new StdClass();
			$usuario->email = $user["email"];
			$usuario->name = $user["name"];

			// call getSubscribers
			$params = array(
				"function"=>"getSubscribers",
				"apiKey"=>$apiKey,
				"email"=>$usuario->email
			);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, 3);

			$result = curl_exec($curl);
			$jsonResult = json_decode($result);

			if (!$jsonResult->status)
			{
				$usuarios_mailrelay = new StdClass;
			}
			else
			{
				$data = $jsonResult->data;
				if (count($data)>0)
				{
					$usuarios_mailrelay = $data[0];
				}
				else
				{
					$usuarios_mailrelay = new StdClass;
				}
			}

			if ($usuarios_mailrelay->email == $usuario->email)
			{
				// call deleteSubscriber
				$params = array(
					"function"=>"deleteSubscriber",
					"apiKey"=>$apiKey,
					"email"=>$usuario->email,
				);
				$post = http_build_query($params);

				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($curl);
				$jsonResult = json_decode($result);
			}
		}
	}
}
