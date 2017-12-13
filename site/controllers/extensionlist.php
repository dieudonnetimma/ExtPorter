<?php
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
 
// No direct access.
	defined('_JEXEC') or die;
	
	require_once JPATH_COMPONENT.'/controller.php';
	
	/**
	 * Sliders list controller class.
	 */
	class ExtporterControllerExtensionlist extends ExtporterController
	{
		/**
		 * Proxy for getModel.
		 * @since	1.6
		 */
		public function &getModel($name = 'Extensionlist', $prefix = 'ExtporterModel', $config = array())
		{
			$model = parent::getModel($name, $prefix, array('ignore_request' => true));
			return $model;
		}
	}
