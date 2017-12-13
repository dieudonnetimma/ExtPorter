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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_extporter', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();	

?>

<div class="extensionsdetails-edit front-end-edit">
    <?php if (!empty($this->item->extid)): ?>
        <h1>Edit <?php echo $this->item->extid; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>
    <form id="form-extensionsdetails" action="<?php echo JRoute::_('index.php?option=com_extporter&task=extensionsdetails.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
    	<div class="control-group">
                <div class="controls">
                    <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                    <a class="btn" href="<?php echo JRoute::_('index.php?option=com_extporter&task=extensionsdetails.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
                </div>
            </div>
             <div class="form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
    
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_EXTPORTER_TITLE_EXTENSIONSDETAILS', true)); ?>
            <div class="row-fluid">
                <div class="span10 form-horizontal">
                    <fieldset class="adminform">
    	        
    			<?php echo $this->form->getInput('extid'); ?>
    			<?php echo $this->form->getInput('ordering'); ?>
    			<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
    			<input type="hidden" name="jform[checked_out]" value="<?php if(isset($this->item->checked_out)){
    			 echo $this->item->checked_out;}else{ echo JFactory::getUser()->id;} ?>" />
    			<input type="hidden" name="jform[checked_out_time]" value="<?php if(isset($this->item->checked_out_time)){
    			 echo $this->item->checked_out_time;}else{ echo date("Y-m-d H:i:s") ;} ?>" />
    <?php if(empty($this->item->created_by)): ?>
    	<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
    <?php else: ?>
    	<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
    <?php endif; ?>
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
    </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
       
                
    <?php if (JFactory::getUser()->authorise('core.admin','extporter')): ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
                            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
    
            <?php endif; ?>
        	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
                <input type="hidden" name="option" value="com_extporter" />
                <input type="hidden" name="task" value="extensionsdetails.save" />
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
