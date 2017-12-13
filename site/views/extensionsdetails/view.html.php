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
 * View to Edit extension
 */
class ExtporterViewExtensionsdetails extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;
    /**
    * Display the view
    */
        public function display($tpl = null) {
    		
            $app = JFactory::getApplication();
            $user = JFactory::getUser();
    
            $this->state = $this->get('State');
            $this->item = $this->get('Item');
            $this->params = $app->getParams('com_extporter');
    		$this->setLayout('Edit');
    		$this->form		= $this->get('Form');
    	 	if (empty($this->item->extid))
                {
                    $authorised = $user->authorise('core.create', 'com_extporter');
                }
                else
                {
                    $authorised = $user->authorise('core.edit', 'com_extporter');
                }
                if ($authorised !== true)
                {
                    $app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
                    $app->setHeader('status', 403, true);
    
                    return false;
                }
    
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
