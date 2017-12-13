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
     * General Controller of extporter component
     */
    class ExtporterController extends JControllerLegacy
    {
            /**
             * display task
             *
             * @return void
             */
            function display($cachable = false) 
            {
                    // set default view if not set
                    $input = JFactory::getApplication()->input;
                    $input->set('view', $input->getCmd('view', 'extporter'));
    
                    // call parent behavior
                    parent::display($cachable);
            }
    }
