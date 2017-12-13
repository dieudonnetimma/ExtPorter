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
 
 		
 		

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_extporter')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by com_extporter
$controller	= JControllerLegacy::getInstance('Extporter');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
