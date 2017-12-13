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
 * View to  Show the Data
 */
class ExtporterViewExtensionlist extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;
    /**
    * Display the view
    */
        public function display($tpl = null) {
    		
            $user = JFactory::getUser();
    
             $app = JFactory::getApplication();
             $this->params = $app->getParams('com_extporter');
                         $state = $this->params->get('state');
                         if(!empty($state))
                             $this->getModel()->setState('filter.state', $state);
             
                         $search = $this->params->get('search');
                         if(!empty($search))
                             $this->getModel()->setState('filter.search', $search);
             
                         $created_by = $this->params->get('created_by');
                         if(!empty($created_by))
                             $this->getModel()->setState('filter.search',$created_by);
             
                         $ordering = $this->params->get('ordering');
                         if(!empty($ordering))
                             $this->getModel()->setState('list.ordering',$ordering);
             
                         $direction = $this->params->get('direction');
                         if(!empty($direction))
                             $this->getModel()->setState('list.direction', $direction);
             
                         $start = $this->params->get('start');
                         if(!empty($start))
                             $this->getModel()->setState('list.start', $start);
             
                         $limit = $this->params->get('limit');
                         if(!empty($limit))
                             $this->getModel()->setState('list.limit', $limit);
                       $title = $this->params->get('title');
       	                     if(!empty($title))
       	                         $this->getModel()->setState('filter.title', $title);
                       $type = $this->params->get('type');
       	                     if(!empty($type))
       	                         $this->getModel()->setState('filter.type', $type);
                       $extid = $this->params->get('extid');
       	                     if(!empty($extid))
       	                         $this->getModel()->setState('filter.extid', $extid);
    
            	 
           		 $this->items = $this->get('Items');
            	 $this->pagination = $this->get('Pagination');
            	 $this->state = $this->get('State');
            	 $this->filterForm    = $this->get('FilterForm');
            	 $this->activeFilters = $this->get('ActiveFilters');
            	 
            
            $this->params = $app->getParams('com_extporter');
            
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                throw new Exception(implode("\n", $errors));
            }
    
            $this->_prepareDocument();
    
            parent::display($tpl);
    }
    
    /**
    * Prepares the document
    */
       protected function _prepareDocument() {
           $app = JFactory::getApplication();
           $menus = $app->getMenu();
           $title = null;
    
           // Because the application sets a default page title,
           // we need to get it from the menu item itself
           $menu = $menus->getActive();
           if ($menu) {
               $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
           } else {
               $this->params->def('page_heading', JText::_('COM_EXTPORTER_DEFAULT_PAGE_TITLE'));
           }
           $title = $this->params->get('page_title', '');
           if (empty($title)) {
               $title = $app->get('sitename');
           } elseif ($app->get('sitename_pagetitles', 0) == 1) {
               $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
           } elseif ($app->get('sitename_pagetitles', 0) == 2) {
               $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
           }
           $this->document->setTitle($title);
    
           if ($this->params->get('menu-meta_description')) {
               $this->document->setDescription($this->params->get('menu-meta_description'));
           }
    
           if ($this->params->get('menu-meta_keywords')) {
               $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
           }
    
           if ($this->params->get('robots')) {
               $this->document->setMetadata('robots', $this->params->get('robots'));
           }
       }
    } 
