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

<h1><?php echo JText::_('Ha finalizado el proceso de sincronización:'); ?></h1>

<?php
	//We check the Joomla! version, to send a form or another
	$version = substr(JVERSION,0,3);
	if($version == "1.6"){
?>
		<fieldset class="adminform">
			<legend><?php echo JText::_('Se han sincronizado:'); ?></legend>
			<ul class="adminformlist">
				<li>
					<?php
						
						$synced = JRequest::getVar('synced', '');
						
						if(!empty($synced)){
							if($synced == 1){
								echo "<br/><b>" . $synced . " " . JText::_('usuario en total.') . "</b><br/><br/>";
							}else{
								echo "<br/><b>" . $synced . " " . JText::_('usuarios en total.') . "</b><br/><br/>";							}	
						}
					
					?>
				</li>
			</ul>
		</fieldset>
	<?php
		}else{
	?>
		<fieldset class="adminform">
		<legend><?php echo JText::_('Se han sincronizado:'); ?></legend>
		<table class="admintable">
			<tr>
				<td>
					<?php
						
						$synced = JRequest::getVar('synced', '');
						
						if(!empty($synced)){
							if($synced == 1){
								echo "<br/><b>" . $synced . " " . JText::_('usuario en total.') . "</b><br/><br/>";
							}else{
								echo "<br/><b>" . $synced . " " . JText::_('usuarios en total.') . "</b><br/><br/>";							}							
						}
					
					?>
				</td>
			</tr>	
		</table>		
		</fieldset>	
	<?php
		}
	?>		


