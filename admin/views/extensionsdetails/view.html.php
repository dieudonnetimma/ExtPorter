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

	jimport('joomla.application.component.view');

	/**
	 * View to edit a extensionsdetails
	 */
	class ExtporterViewExtensionsdetails extends JViewLegacy {
	
	    protected $state;
	    protected $item;
	    protected $form;
	    
	    /**
	    	* Display the view
	    	*/
	    	public function display($tpl = null) {
	    	$this->setLayout('Edit');
	    	$this->state = $this->get('State');
	    	$this->item = $this->get('Item');
	    	$this->form = $this->get('Form');
	    	
	    	// Check for errors.
	    	if (count($errors = $this->get('Errors'))) {
	    	throw new Exception(implode("\n", $errors));
	    	}
	    	
	    	$this->addToolbar();
	    	parent::display($tpl);
	    	}
	    	
	    /**
	         * Add the page title and toolbar.
	         */
	        protected function addToolbar() {
	            JFactory::getApplication()->input->set('hidemainmenu', true);
	    
	            $user = JFactory::getUser();
	            $isNew = ($this->item->extid == 0);
	            if (isset($this->item->checked_out)) {
	                $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
	            } else {
	                $checkedOut = false;
	            }
	            $canDo = ExtporterHelper::getActions();
	    
	            JToolBarHelper::title(JText::_('COM_EXTPORTER_TITLE_EXTENSIONSDETAILS'), 'extensionsdetails.png');
	    
	            // If not checked out, can save the item.
	            if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {
	    
	                JToolBarHelper::apply('extensionsdetails.apply', 'JTOOLBAR_APPLY');
	                JToolBarHelper::save('extensionsdetails.save', 'JTOOLBAR_SAVE');
	            }
	            if (!$checkedOut && ($canDo->get('core.create'))) {
	                JToolBarHelper::custom('extensionsdetails.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
	            }
	            // If an existing item, can save to a copy.
	            if (!$isNew && $canDo->get('core.create')) {
	                JToolBarHelper::custom('extensionsdetails.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
	            }
	            if (empty($this->item->id)) {
	                JToolBarHelper::cancel('extensionsdetails.cancel', 'JTOOLBAR_CANCEL');
	            } else {
	                JToolBarHelper::cancel('extensionsdetails.cancel', 'JTOOLBAR_CLOSE');
	    		}
	    	}
	    	
	}		
