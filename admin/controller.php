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

jimport('joomla.application.component.controller');

class MailrelaySyncController extends JController
{
	/**
	* Here we try to verify user data agains the API
	*/
	function verify()
	{
	
		$username = JRequest::getVar('username', '');
		$password = JRequest::getVar('password', '');
		$hostname = JRequest::getVar('hostname', '');
		
		// First thing, authenticate
		$url = 'http://'. $hostname .'/ccm/admin/api/version/2/&type=json';
		$curl = curl_init($url);
		
		$params = array(
			'function' => 'doAuthentication',
			'username' => $username,
			'password' => $password
		);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		// Call the page, it will return a json
		$result = curl_exec($curl);		
		
		$jsonResult = json_decode($result);
		
		if (!$jsonResult->status) {
			//If the user can't validate, we will show the login from again
			JRequest::setVar('status', 'ko');
			JRequest::setVar('template', null);
			JRequest::setVar('message', 'Los datos de usuario son incorrectos.');
			parent::display();

		} else {
		
			//If the user validates correctly, we will get the groups data
			$apiKey = $jsonResult->data;
			
			// Call getGroups
			$params = array(
				'function' => 'getGroups',
				'apiKey' => $apiKey
			);
			
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			
			$result = curl_exec($curl);
			
			$jsonResult = json_decode($result);
			
			if (!$jsonResult->status) {
				JRequest::setVar('message', 'Error, no se han podido obtener los datos.');
			} else {
				JRequest::setVar('groups', $jsonResult->data);
			}
			
			JRequest::setVar('status', 'ok');
			JRequest::setVar('apikey', $apiKey);
			JRequest::setVar('hostname', $hostname);			
			JRequest::setVar('template', 'groups');		
			parent::display();

		}
	}
	
	/**
	* Now we start the sync process
	*/
	function sync()
	{
		$model =& $this->getModel();
		$usuarios = $model->getAllUsers();
		
		$apiKey = JRequest::getVar('apikey', '');
		$hostname = JRequest::getVar('hostname', '');
		$grupos = JRequest::getVar('grupos', '');
		
		$synced_users = 0;	
		
		$url = 'http://'. $hostname .'/ccm/admin/api/version/2/&type=json';
		$curl = curl_init($url);				
		
		//We loop through all users from our Joomla! installation
		foreach($usuarios AS $usuario){		
			
			// Call getSubscribers
			$params = array(
				'function' => 'getSubscribers',
				'apiKey' => $apiKey,
				'email' => $usuario->email
			);
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);		
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			
			$result = curl_exec($curl);
			
			$jsonResult = json_decode($result);	

			if (!$jsonResult->status) {
				$usuarios_mailrelay = new StdClass;
			} else {
				$data = $jsonResult->data;
				$usuarios_mailrelay = $data[0];
			}
			
			//We check if the user already exists in the API
			if($usuarios_mailrelay->email == $usuario->email){

				// Call updateSubscriber							
				$params = array(
					'function' => 'updateSubscriber',
					'apiKey' => $apiKey,
					'id' => $usuarios_mailrelay->id,
					'email' => $usuario->email,
					'name' => $usuario->name,
					'groups' => $grupos
	
				);
				
				//Array ( [function] => updateSubscriber [apiKey] => 22474eb041597f726721ff60a5400d5f6a9f24da [id] => 1 [email] => fernando@consultorpc.com [name] => Super User [groups] => Array ( [0] => 3 [1] => 2 [2] => 1 ) ) Array ( [function] => updateSubscriber [apiKey] => 22474eb041597f726721ff60a5400d5f6a9f24da [id] => 12 [email] => prueba@gmail.com [name] => prueba2 [groups] => Array ( [0] => 3 [1] => 2 [2] => 1 ) ) 
				
				$post = http_build_query($params);
				
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);				
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				
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
					'groups' => $grupos
	
				);
				
				$post = http_build_query($params);
				
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);				
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				
				$result = curl_exec($curl);
				
				$jsonResult = json_decode($result);	
			
				if ($jsonResult->status) {
					$synced_users++;
				}
			}			
		}
		
		//Now that the sync process has ended, we redirect to the show process
		$this->setRedirect('index.php?option=com_mailrelay_sync&task=show&template=synced&synced=' . $synced_users);
		$this->redirect();
		
	}
	
	/**
	* And we show the last screen
	*/
	function show()
	{		
		parent::display();
	}

	function display($cachable=false)
	{
		// Set the submenu
                MailrelaySyncHelper::addSubmenu('messages');

		$app = JFactory::getApplication();
		$app->set('JComponentTitle', JText::_("COM_MAILRELAY_TITLE"));

                // Set the toolbar
		parent::display($cachable);
	}

	function settings()
	{
	}

}

?>
