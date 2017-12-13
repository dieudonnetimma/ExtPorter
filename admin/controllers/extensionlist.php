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


jimport('joomla.application.component.controlleradmin');

/**
 * Extensionlist list controller .
 * @generated
 */
class ExtporterControllerExtensionlist extends JControllerAdmin
{
	/**
	* Constructor.
	*
	* @param   array  $config	An optional associative array of configuration settings.
	* @return  ExtporterControllerExtensionlist
	* @see     JController
	* @since   1.6
	* @generated
	*/
	   public function __construct($config = array())
	   {
	       parent::__construct($config);
	
	   }
	
	/**
	* save the order.
	*
	* @return  Integer
	* @generated
	*/
	    public function saveordering(){
	        $app = JFactory::getApplication();
	        $ids = $app->input->get('cid', array(), 'array');
	        $model = $this->getModel('extensionlist');
	        $result = $model->saveOrdering($ids);
	        if($result)
	        {
	        echo new JResponseJson($result);
	        }
	    }
	/**
	 * Overwrite the  getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Extensionsdetails', $prefix = 'ExtporterModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
