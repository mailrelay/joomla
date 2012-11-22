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

<h1><?php echo JText::_('Introduce tus datos:'); ?></h1>

<form action="index.php" method="post" name="adminForm">

	<?php
		//We check the Joomla! version, to send a form or another
		$version = substr(JVERSION,0,3);
		if($version == "1.6"){
	?>

			<fieldset class="adminform">
				<legend><?php echo JText::_('Introduce tus datos y haz clic en el botón enviar'); ?></legend>
				<ul class="adminformlist">
					<li><label><?php echo JText::_('Host'); ?>:</label> <input type="text" name="hostname" value="" /></li>
					<li><label><?php echo JText::_('Usuario'); ?>:</label> <input type="text" name="username" value="" /></li>
					<li><label><?php echo JText::_('Contraseña'); ?>:</label> <input type="password" name="password" value="" /></li>
					<li>
						<label>&nbsp;</label>
						<input type="hidden" name="option" value="com_mailrelay_sync" />
						<input type="hidden" name="task" value="verify" />
						
						<input type="submit" name="send" value="<?php echo JText::_('Enviar'); ?>" />				
					</li>
				</ul>		
			</fieldset>	
	<?php
		}else{
	?>
			<fieldset class="adminform">
			<legend><?php echo JText::_('Introduce tus datos y haz clic en el botón enviar'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label><?php echo JText::_('Host'); ?>:</label>
					</td>		
					<td>
						<input class="inputbox" type="text" name="hostname" value="" />
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label><?php echo JText::_('Usuario'); ?>:</label>		
					</td>
					<td>
						<input class="inputbox" type="text" name="username" value="" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label><?php echo JText::_('Contraseña'); ?>:</label>		
					</td>
					<td>
						<input class="inputbox" type="password" name="password" value="" />
					</td>
				</tr>
				<tr>
					<td class="key">
						&nbsp;		
					</td>
					<td>
						<input type="hidden" name="option" value="com_mailrelay_sync" />
						<input type="hidden" name="task" value="verify" />
						
						<input type="submit" name="send" value="<?php echo JText::_('Enviar'); ?>" />
					</td>
				</tr>				
			</table>
		
			</fieldset>	
	<?php
		}
	?>

</form>

<?php
	
	$message = JRequest::getVar('message', '');
	
	if(!empty($message)){
	
		?>
		<dl id="system-message">
		<dt class="error">Error</dt>
		<dd class="error message">
			<ul>
				<li><?php echo JText::_($message); ?></li>
			</ul>
		</dd>
		</dl>
		<?php
	}

?>