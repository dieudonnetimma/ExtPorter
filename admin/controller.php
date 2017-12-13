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
     
     		
     		
    
    // import Joomla controller library
    jimport('joomla.application.component.controller');
    
    /**
     * General Controller of Extporter component
     */
    class ExtporterController extends JControllerLegacy
    {
            /**
             * display task
             *
             * @return void
             */
             public function display($cachable = false, $urlparams = false) 
             {
             	
             	  require_once JPATH_COMPONENT . '/helpers/extporter.php';
             	  $view = JFactory::getApplication()->input->getCmd('view', 'Extporters');
             	  JFactory::getApplication()->input->set('view', $view);
             	  parent::display($cachable, $urlparams);
             	  return $this;
             	}
    }
