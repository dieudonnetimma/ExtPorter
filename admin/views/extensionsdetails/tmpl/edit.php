<?php
	/**
	* @version 1.0.0
	* @category Joomla component
	* @subpackage com_extporter.site
	* @name extporterView
	* @author Dieudonne Timma, <dieudonne.timma.meyatchie@mni.thm.de>
	* @copyright GNU 3
	* @license Open Source
	*/

	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('behavior.keepalive');
	
	// Import CSS
	$document = JFactory::getDocument();
	$document->addStyleSheet('components/com_extporter/assets/css/extporter.css');
	?>
	<script type="text/javascript">
	    js = jQuery.noConflict();
	    js(document).ready(function() {
	        
	    });
	
	    Joomla.submitbutton = function(task)
	    {
	        if (task == 'extensionsdetails.cancel') {
	            Joomla.submitform(task, document.getElementById('extensionsdetails-form'));
	        }
	        else {
	            
	            if (task != 'extensionsdetails.cancel' && document.formvalidator.isValid(document.id('extensionsdetails-form'))) {
	                
	                Joomla.submitform(task, document.getElementById('extensionsdetails-form'));
	            }
	            else {
	                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	            }
	        }
	    }
	</script>
	<form action="<?php echo JRoute::_('index.php?option=com_extporter&layout=edit&extid=' . (int) $this->item->extid); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="extensionsdetails-form" class="form-validate">
	
	    <div class="form-horizontal">
	        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
	
	        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_EXTPORTER_TITLE_EXTENSIONSDETAILS', true)); ?>
	        <div class="row-fluid">
	            <div class="span10 form-horizontal">
	                <fieldset class="adminform">
	                <input type="hidden" name="jform[extid]" value="<?php echo $this->item->extid; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[published]" value="<?php if($this->item->extid != 0) echo $this->item->state; else echo 1;?>"/>
				<input type="hidden" name="jform[checked_out]" value="<?php if(isset($this->item->checked_out)){
				 echo $this->item->checked_out;}else{ echo JFactory::getUser()->id;} ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php if(isset($this->item->checked_out_time)){
				 echo $this->item->checked_out_time;}else{ echo date("Y-m-d H:i:s") ;} ?>" />
				
			<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	
				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	
				<?php } ?>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('extname'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('extname'); ?></div>
				</div>
				
				<div class="controls"><?php echo $this->form->getInput('model'); ?></div>

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('targetzip'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('targetzip'); ?></div>
				</div>
				 
				</fieldset>
			</div>
			</div>
	        <?php echo JHtml::_('bootstrap.endTab'); ?>
	        
		    <?php if (JFactory::getUser()->authorise('core.admin','extporter')) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
				<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
	
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			
	
	        <input type="hidden" name="task" value="" />
	        <?php echo JHtml::_('form.token'); ?>
	
	    </div>
	</form>
