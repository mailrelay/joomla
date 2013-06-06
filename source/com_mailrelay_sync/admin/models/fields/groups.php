<?php
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldGroups extends JFormField
{
    protected $type = 'groups';

    public function getInput()
    {
	// read form values to generate groups array
	$host = $this->form->getValue("host");
	$apiKey = $this->form->getValue("apiKey");

	// retrieve groups
	$model = JModelLegacy::getInstance("Settings", "MailrelaySyncModel");
	$result = $model->verify($host, $apiKey);

	$entry_groups = array();
	if ($result && is_array($result))
	{
		foreach($result as $item)
		{
			if ($item->enable && $item->visible)
			{
				$entry_groups[$item->id] = $item->name;
			}
		}
	}

	if (!is_array($this->value))
	{
		// grupos seleccionados
		$selected_groups = explode(",", $this->value);
	}
	else
	{
		$selected_groups = $this->value;
	}

	return JHtml::_("select.genericlist", $entry_groups, $this->name, 'size="10" multiple', 'value', 'text', $selected_groups, $this->id);
    }
}
