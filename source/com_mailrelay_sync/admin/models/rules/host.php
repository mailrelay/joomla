<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * Form Rule class for the Joomla Framework.
 */

class JFormRuleHost extends JFormRule
{
	public function test(&$element, &$value, $group = null, &$input = null, &$form = null)
	{
		// try to connect with that settings

		// validacion de datos correctos contra mailrelay
		$model = JModelLegacy::getInstance("Settings", "MailrelaySyncModel");
		$result = $model->verify($input->get("host"), $input->get("user"), $input->get("password"));
		if (!$result)
		{
			// error de datos incorrectos
			$error = JText::_("COM_MAILRELAY_SYNC_ERROR_FAULT_DATA");
			return new JException($error);
		}
		else
		{
			// parse groups
			$entry_groups = array();
			foreach($result as $item)
			{
				if ($item->enable && $item->visible)
				{
					$entry_groups[] = $item->id;
				}
			}
			// validacion de grupos
			$groups = $input->get("groups");
			if (!$groups)
			{
				// error, you must select it
				$error = JText::_("COM_MAILRELAY_SYNC_ERROR_NO_GROUPS");
				return new JException($error);
			}
			else
			{
				// check if the selected groups are in the available groups
				foreach($groups as $group)
				{
					if (!in_array($group, $entry_groups))
					{
						$error = JText::_("COM_MAILRELAY_SYNC_GROUP_NOT_AVAILABLE");
						return new JException($error);
					}
				}
			}

		}

		return true;

	}
}
