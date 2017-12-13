<?php
 /**
 * @version 1.0.0
 * @category Joomla component
 * @package     Joomla.Administrator
 * @subpackage  com_extporter
 * @name extporter 
 * @author Dieudonne Timma   <dieudonne.timma.meyatchie@mni.thm.de> 
 * @copyright GNU 3
 * @license Open Source
 */
 defined('_JEXEC') or die('Restricted access');
 
 		
 		
?>
<div >
	</div>
	<p class="text-center"> <h1><?php echo "Welcome to ". JText::_('COM_EXTPORTER') . " ". JText::_('COM_EXTPORTER_HOME'); ?> </h1>
	<h4>This component can export installed extensions with the data and create a eJSL-Model of the exported extensions</h4>
	 </p> 
	<div id="cpanel" class='cpanel'>
	<?php foreach ($this->views as $view)
	{
	?>
	    <div class="icon">
	        <h3><a href='<?php echo $view['url']; ?>'
	            <span><?php echo $view['title']; ?></span>
	        </a></h3>
	        <br />
	    </div>
	<?php
	}
	?>
</div>  
<p>This component is generated with the Joomdd tools, for more information <a target="_blank" href="https://github.com/icampus/JooMDD">see here</a></p>	
