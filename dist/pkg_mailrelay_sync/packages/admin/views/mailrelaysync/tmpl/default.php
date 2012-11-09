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
<form action="index.php" method="post" name="adminForm">

<input type="hidden" name="option" value="com_mailrelay_sync" />
<input type="hidden" name="task" value="" />

</form>
<h1><?php echo JText::_("COM_MAILRELAY_SYNC_INTRO"); ?></h1>
<?php echo JText::_("COM_MAILRELAY_SYNC_EXPLANATION"); ?>
