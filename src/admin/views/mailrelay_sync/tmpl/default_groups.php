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

?>

<?php
	//We check the Joomla! version, to send a form or another
	$version = substr(JVERSION,0,3);
	if($version == "1.5"){
?>
	<script language="javascript" type="text/javascript">
		<!--	
		function submitbutton()
		{
			var form = document.adminForm;
	
			if (form.grupos.selectedIndex == "-1"){
				alert( "Debes seleccionar al menos un grupo" );
			} else {
				form.submit();
			} 
		}
		//-->
	</script>
<?php
	}
?>

<style>
	.validation-advice{
		color: red;
		font-weight: bold;
	}
</style>

<h1><?php echo JText::_('Selecciona los grupos que quieres sincronizar:'); ?></h1>


<form action="index.php" method="post" name="adminForm" id="adminForm">

	<?php
		//We check the Joomla! version, to send a form or another
		$version = substr(JVERSION,0,3);
		if($version == "1.6"){
	?>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_('Puedes seleccionar más de un grupo cada vez'); ?></legend>
			<ul class="adminformlist">
				<li>
					<label><?php echo JText::_('Grupos'); ?>:</label> 
					<select name="grupos[]" multiple="multiple" class="required" title="<?php echo JText::_('Debes seleccionar al menos un grupo'); ?>.">
						<?php
							$grupos = JRequest::getVar('groups', '');
							foreach($grupos AS $grupo){
						
						?>
							<option value="<?php echo $grupo->id; ?>"><?php echo $grupo->name; ?></option>
						<?php
							}
						?>
					</select>
				</li>
				<li>
					<label>&nbsp;</label> 
					<input type="hidden" name="option" value="com_mailrelay_sync" />
					<input type="hidden" name="task" value="sync" />
					<input type="hidden" name="apikey" value="<?php echo JRequest::getVar('apikey', '') ?>" />
					<input type="hidden" name="hostname" value="<?php echo JRequest::getVar('hostname', '') ?>" />		
					<input type="submit" name="send" value="<?php echo JText::_('Enviar'); ?>" />			
				</li>
			</ul>
			
		</fieldset>
		
		<id id="formResult" style="color: red">
		
		</id>
		
	<?php
		}else{
	?>
			<fieldset class="adminform">
			<legend><?php echo JText::_('Puedes seleccionar más de un grupo cada vez'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label><?php echo JText::_('Grupos'); ?>:</label>
					</td>		
					<td>
						<select id="grupos" name="grupos[]" multiple="multiple" class="inputbox required" title="<?php echo JText::_('Debes seleccionar al menos un grupo'); ?>.">
							<?php
								$grupos = JRequest::getVar('groups', '');
								foreach($grupos AS $grupo){
							
							?>
								<option value="<?php echo $grupo->id; ?>"><?php echo $grupo->name; ?></option>
							<?php
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label>&nbsp;</label>	
					</td>
					<td>
						<input type="hidden" name="option" value="com_mailrelay_sync" />
						<input type="hidden" name="task" value="sync" />
						<input type="hidden" name="apikey" value="<?php echo JRequest::getVar('apikey', '') ?>" />
						<input type="hidden" name="hostname" value="<?php echo JRequest::getVar('hostname', '') ?>" />		
						<input type="button" name="send" value="<?php echo JText::_('Enviar'); ?>" onclick="javascript: submitbutton()" />
					</td>
				</tr>		
			</table>		
			</fieldset>	
	<?php
		}
	?>		

</form>

<?php
	//We check the Joomla! version, to send a form or another
	$version = substr(JVERSION,0,3);
	if($version == "1.6"){
?>
	<script>
	
	window.addEvent('domready', function(){
	
	  var myForm = document.id('adminForm'), myResult = document.id('formResult');
	
	  // Labels over the inputs.
	  myForm.getElements('[type=text], textarea').each(function(el){
	    new OverText(el);
	  });
	
	  // Validation.
	  new Form.Validator.Inline(myForm, {useTitles: true});
	
	});
	
	</script>
<?php
	}
?>

<?php
	
	$message = JRequest::getVar('message', '');
	
	if(!empty($message)){
		echo "<br/><br/>" . JText::_($message);
	}

?>