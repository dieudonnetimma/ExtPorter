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
	     
	     		
	     		
	    
	    
	    // import joomla controller library
	    jimport('joomla.application.component.controller');
	    
	    // Get an instance of the controller prefixed by Extporter
	    $controller = JControllerLegacy::getInstance('Extporter');
	    
	    // Perform the Request task
	    $input = JFactory::getApplication()->input;
	    $controller->execute($input->getCmd('task'));
	    
	    // Redirect if set by the controller
	    $controller->redirect();
