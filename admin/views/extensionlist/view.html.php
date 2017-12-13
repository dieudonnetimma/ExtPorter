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
	defined('_JEXEC') or die('Restricted access');

/**
* @description extensionView for extporter
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
jimport('joomla.filesystem.path');
include_once JPATH_ROOT . "/administrator/components/com_extporter/helpers/extractor.php";
/**
* extporterView class for component com_extporter
*
* @category Joomla.Component. Admin
* @package com_extporter."admin"
* @generated
*/
class ExtporterViewExtensionlist extends JViewLegacy
{
 	protected $items;

	protected $pagination;

	protected $state;
    public $tabltest;
	
	/**
	* loads model data into view context
	*
	* @param   string  $tpl  the name of the template to be used
	*
	* @return void
	* @generated
	*/
	    public function display($tpl = null) {
	        $this->state = $this->get('State');
	        $this->items = $this->get('Items');
	        $this->pagination = $this->get('Pagination');
	        $this->filterForm    = $this->get('FilterForm');
	        $this->activeFilters = $this->get('ActiveFilters');
            $ex = new Extractor();
//$ter = $ex->fileExtractor("C:/xampp/htdocs/mddjoomla/administrator/components/com_ccgslider/ccgslider.xml","C:/xampp/htdocs/mddjoomla");
           // $ter = $ex->fileExtractor("C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/com_thm_organizer.xml","C:/xampp/htdocs/mddjoomla");
           // $ter = $ex->fileExtractor("C:/xampp/htdocs/mddjoomla/administrator/components/com_brueckenkurs/com_brueckenkurs.xml","C:/xampp/htdocs/mddjoomla");
//$ex->scanAllFileInFolder("C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates", array());
//$ter = $ex->fileExtractor("C:/Users/dieudonne/Desktop/Arbeit 2017/pkg_rcpusermanager_1.0.2/com_rcpusermanager","C:/xampp/htdocs/mddjoomla");

            foreach ($ex->dbAllTable as $val){
                if($val->isAlreadyShow ==3 ){
                    print_r(print_r($val));
                    echo "<br/><br/>";
                }
            }
	
	        // Check for errors.
	        if (count($errors = $this->get('Errors'))) {
	            throw new Exception(implode("/n", $errors));
	        }
	
	        ExtporterHelper::addSubmenu('extensionlist');
	
	        $this->addToolbar();
	
	        $this->sidebar = JHtmlSidebar::render();
	        parent::display($tpl);
	    }
	/**
	* Add the page title and toolbar.
	*
	* @since	1.6
	* @generated
	*/
	   protected function addToolbar() {
	       require_once JPATH_COMPONENT . '/helpers/extporter.php';
	
	       $state = $this->get('State');
	       $canDo = ExtporterHelper::getActions($state->get('filter.category_id'));
	
	       JToolBarHelper::title(JText::_('COM_EXTPORTER_TITLE_EXTENSIONLIST'));
	
	       //Check if the form exists before showing the add/edit buttons
	       $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/extensionsdetails';
	       if (file_exists($formPath)) {
	
	           if ($canDo->get('core.create')) {
	               JToolBarHelper::addNew('extensionsdetails.add', 'JTOOLBAR_NEW');
	           }
	
	           if ($canDo->get('core.edit') && isset($this->items[0])) {
	               JToolBarHelper::editList('extensionsdetails.edit', 'JTOOLBAR_EDIT');
	           }
	       }
	
	       if ($canDo->get('core.edit.state')) {
	
	           if (isset($this->items[0]->state)) {
	               JToolBarHelper::divider();
	               JToolBarHelper::custom('extensionlist.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
	               JToolBarHelper::custom('extensionlist.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
	           } else if (isset($this->items[0])) {
	               //If this component does not use state then show a direct delete button as we can not trash
	               JToolBarHelper::deleteList('', 'extensionlist.delete', 'JTOOLBAR_DELETE');
	           }
	
	           if (isset($this->items[0]->state)) {
	               JToolBarHelper::divider();
	               JToolBarHelper::archiveList('extensionlist.archive', 'JTOOLBAR_ARCHIVE');
	           }
	           if (isset($this->items[0]->checked_out)) {
	               JToolBarHelper::custom('extensionlist.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
	           }
	       }
			
	       //Show trash and delete for components that uses the state field
	       if (isset($this->items[0]->state)) {
	           if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
	               JToolBarHelper::deleteList('', 'extensionlist.delete', 'JTOOLBAR_EMPTY_TRASH');
	               JToolBarHelper::divider();
	           } else if ($canDo->get('core.edit.state')) {
	               JToolBarHelper::trash('extensionlist.trash', 'JTOOLBAR_TRASH');
	               JToolBarHelper::divider();
	           }
	       }
	
	       if ($canDo->get('core.admin')) {
	           JToolBarHelper::preferences('com_extporter');
	       }
	
	       JHtmlSidebar::setAction('index.php?option=com_extporter&view=extensionlist');
	
			}
	
	
}
