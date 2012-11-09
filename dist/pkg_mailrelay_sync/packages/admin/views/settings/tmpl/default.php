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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
<form action="<?php echo JRoute::_("index.php?option=com_mailrelay_sync&layout=default"); ?>" method="post" name="adminForm" id="defaultForm" class="form-validate">
	<legend><?php echo JText::_('COM_MAILRELAY_SYNC_FORM_DEFAULT_DETAILS'); ?></legend><br />

	<fieldset name="general">
	<legend><?php echo JText::_("COM_MAILRELAY_GENERAL_CONFIG_LABEL"); ?></legend>
	<ul class="adminformlist">
		<?php
		$fields = $this->form->getFieldset("general");

		foreach($fields as $name=>$field)
		{
			if ($field->input)
			{
				?><li style="padding:5px;"><span style="width:200px;"><?php echo $field->label; ?></span><?php echo $field->input; ?></li><?php
			}
		}
		?>
		</ul>
	</fieldset>

	<fieldset name="params">
	<legend><?php echo JText::_("COM_MAILRELAY_SYNC_CONFIG_LABEL"); ?></legend>
	<ul class="adminformlist">
		<?php
		$fields = $this->form->getFieldset("params");

		foreach($fields as $name=>$field)
		{
			if ($field->input)
			{
				?><li style="padding:5px;"><span style="width:200px;"><?php echo $field->label; ?></span><?php echo $field->input; ?></li><?php
			}
		}
		?>
		</ul>
	</fieldset>

	<div>
		<input type="hidden" name="task" value="settings" />
		<?php echo JHtml::_("form.token"); ?>
	</div>
</form>
