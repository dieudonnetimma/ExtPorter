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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_extporter');
$saveOrder	= $listOrder == 'a.ordering';
$model = $this->getModel();
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_extporter&task=extensionlist.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'ExtensionlistList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_extporter&view=extensionlist'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<?php
      echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
      ?>      
	
<table class="table table-striped" id="ExtensionlistList">
		<thead>
			<tr>
           <?php if (isset($this->items[0]->ordering)): ?>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
           <?php endif; ?>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
           <?php if (isset($this->items[0]->state)): ?>
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
    
   <?php if (isset($this->items[0]->extid)): ?>
		<th width="1%" class="nowrap center hidden-phone">
			<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_EXTID', 'a.extid', $listDirn, $listOrder); ?>
		</th>
   <?php endif; ?>
	</tr>
</thead>

<tbody>
<?php foreach ($this->items as $i => $item) :
	$ordering   = ($listOrder == 'a.ordering');
   $canCreate	= $user->authorise('core.create',		'com_extporter');
   $canEdit	= $user->authorise('core.edit',			'com_extporter');
   $canCheckin	= $user->authorise('core.manage',		'com_extporter');
   $canChange	= $user->authorise('core.edit.state',	'com_extporter');
	?>
	<tr class="row<?php echo $i % 2; ?>">
       
   <?php if (isset($this->items[0]->ordering)): ?>
		<td class="order nowrap center hidden-phone">
		<?php if ($canChange) :
			$disableClassName = '';
			$disabledLabel	  = '';
			if (!$saveOrder) :
				$disabledLabel    = JText::_('JORDERINGDISABLED');
				$disableClassName = 'inactive tip-top';
			endif; ?>
			<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
				<i class="icon-menu"></i>
			</span>
			<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
		<?php else : ?>
			<span class="sortable-handler inactive" >
				<i class="icon-menu"></i>
			</span>
		<?php endif; ?>
		</td>
   <?php endif; ?>
		<td class="center hidden-phone">
			<?php echo JHtml::_('grid.id', $i, $item->extid); ?>
		</td>
   <?php if (isset($this->items[0]->state)): ?>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->state, $i, 'extensionlist.', $canChange, 'cb'); ?>
		</td>
   <?php endif; ?>
		<?php if ($canEdit) : ?>
		<td>
			<a href="<?php echo JRoute::_(
			'index.php?option=com_extporter&view=extensionsdetails' . '&extid=' . $item->extid
			. '&title=' . $item->title
			  . '&extid='.(int) $item->extid 
			); ?>">
				<?php echo $this->escape($item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($item->title); ?>
			<?php endif; ?>
			</td>
		<td>
		<?php echo $item->type; ?>
		</td>
		<td>
		<?php echo $item->extid; ?>
		</td>
       <?php if (isset($this->items[0]->extid)): ?>
			<td class="center hidden-phone">
				<?php echo (int) $item->extid; ?>
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
<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>  

