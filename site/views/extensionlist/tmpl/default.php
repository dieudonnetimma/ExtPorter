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
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
	
	$user = JFactory::getUser();
	$userId = $user->get('id');
	$model = $this->getModel();
	$listOrder = $this->state->get('list.ordering');
	$listDirn = $this->state->get('list.direction');
	$canCreate = $user->authorise('core.create', 'com_extporter');
	$canEdit = $user->authorise('core.edit', 'com_extporter');
	$canCheckin = $user->authorise('core.manage', 'com_extporter');
	$canChange = $user->authorise('core.edit.state', 'com_extporter');
	$canDelete = $user->authorise('core.delete', 'com_extporter');
?>

	<form action="<?php echo JRoute::_('index.php?option=com_extporter&view=extensionlist'); ?>" method="post" name="adminForm" id="adminForm">
     <?php
        echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>
    <table class="table table-striped">
        <thead >
            <tr >
                <?php if (isset($this->items[0]->state) && $canEdit ): ?>
        <th width="1%" class="nowrap center">
            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>
    	<th class='left'>
<?php echo JHtml::_('grid.sort',  'COM_EXTPORTER_FORM_LBL_EXTENSION_TITLE', 'a.title', $listDirn, $listOrder); ?>
		</th>
    	<th class='left'>
<?php echo JHtml::_('grid.sort',  'COM_EXTPORTER_FORM_LBL_EXTENSION_TYPE', 'a.type', $listDirn, $listOrder); ?>
		</th>
    	<th class='left'>
<?php echo JHtml::_('grid.sort',  'COM_EXTPORTER_FORM_LBL_EXTENSION_EXTID', 'a.extid', $listDirn, $listOrder); ?>
		</th>
    <?php if (isset($this->items[0]->extid) && $canEdit ): ?>
        <th width="1%" class="nowrap center hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_extid', 'a.extid', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>

    </tr>
    </thead>
     
	<tbody>
	    <?php foreach ($this->items as $i => $item) : ?>
	        <?php $canEdit = $user->authorise('core.edit', 'com_extporter'); ?>
	
					<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_extporter')): ?>
				<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
			<?php endif; ?>
			 <tr class="row<?php echo $i % 2; ?>">
	
	            <?php if (isset($this->items[0]->state)&& $canEdit): ?>
	                <?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
	                <td class="center">
	                    <a class="btn btn-micro <?php echo $class; ?>"
	                    href="<?php echo ($canEdit || $canChange) ? JRoute::_('index.php?option=com_extporter&task=extensionsdetailsedit.publish&extid=' . $item->extid . '&state=' .$item->state ) : '#'; ?>">
	                        <?php if ($item->state == 1): ?>
	                            <i class="icon-publish"></i>
	                        <?php else: ?>
	                            <i class="icon-unpublish"></i>
	                        <?php endif; ?>
	                    </a>
	                </td>
	            <?php endif; ?>
	         
	           
	           <td>
	           	<a href="<?php echo JRoute::_(
	           	'index.php?option=com_extporter&view=extensionsdetails' . '&extid=' . $item->extid
	           	. '&title=' . $item->title
	           	  . '&extid='.(int) $item->extid 
	           	); ?>">
	           		<?php echo $this->escape($item->title); ?></a>
	           <td>
	           <?php echo $item->type; ?>
	           </td>
	           <td>
	           <?php echo $item->extid; ?>
	           </td>
	
	            <?php if (isset($this->items[0]->extid)&& $canEdit): ?>
	                <td class="center hidden-phone">
	                    <?php echo (int)$item->extid; ?>
	                </td>
	            <?php endif; ?>
	
	            	
	
	        </tr>
	    <?php endforeach; ?>
	   
			
	 </tbody>
	 <tfoot>
	     <tr>
	          <td colspan="<?php echo $this->pagination->pagesStop + 5 ;?>">
	             <?php echo $this->pagination->getListFooter(); ?>
	         </td>
	     </tr>
	     </tfoot>
    </table>
  <?php if ($canCreate): ?>
      <a href="<?php echo JRoute::_('index.php?option=com_extporter&view=extensionsdetailsedit&layout=edit&extid=0', false, 2); ?>"
         class="btn btn-success btn-small"><i
              class="icon-plus"></i> <?php echo JText::_('COM_EXTPORTER_ADD_ITEM'); ?></a>
  <?php endif; ?>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

function deleteItem() {
    var item_id = jQuery(this).attr('data-item-id');
    if (confirm("<?php echo JText::_('COM_EXTPORTER_DELETE_MESSAGE'); ?>")) {
        window.location.href = '<?php echo JRoute::_('index.php?option=com_extporter&task=extensionsdetailsedit.remove&extid=') ?>' + item_id;
    }
}
</script>    
