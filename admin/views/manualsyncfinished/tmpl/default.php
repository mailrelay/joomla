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

?>
<h2><?php echo $this->total; ?> <?php echo JText::_("COM_MAILRELAY_TOTAL_USERS_SYNCED"); ?></h2>
