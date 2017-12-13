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

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
/**
* Extensionsdetails controller class.
* @generated
*/
class ExtporterControllerExtensionsdetails extends JControllerForm
{

function __construct() {
	    	$this->view_list = 'extensionlist';
	        parent::__construct();
}


	
}
