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


jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

/**
 * Model to Edit  a Dataitem
 */
class ExtporterModelExtensionsdetails extends JModelForm {
	var $_item = null;
	/**
	* Method to auto-populate the model state.
	*
	* Note. Calling getState in this method will result in recursion.
	*
	* @since	1.6
	*/
	    protected function populateState() {
	        $app = JFactory::getApplication('com_extporter');
	
	        // Load state from the request userState on edit or from the passed variable on default
	        if (JFactory::getApplication()->input->get('layout') == 'edit') {
	            $id = JFactory::getApplication()->getUserState('com_extporter.edit.extensionsdetails.extid');
	        } else {
	            $id = JFactory::getApplication()->input->get('extid');
	            JFactory::getApplication()->setUserState('com_extporter.edit.extensionsdetails.extid', $id);
	        }
	        $this->setState($this->getName().'.extid', $id);
	
	        // Load the parameters.
	        $params = $app->getParams();
	        $params_array = $params->toArray();
	        if (isset($params_array['item_id'])) {
	            $this->setState($this->getName().'.extid', $params_array['item_id']);
	        }
	        $this->setState('params', $params);
	}
	/**
	* Method to check in an item.
	*
	* @param	integer		The id of the row to check out.
	* @return	boolean		True on success, false on failure.
	* @since	1.6
	*/
	   public function checkin($id = null) {
	       // Get the id.
	       $id = (!empty($id)) ? $id : (int) $this->getState($this->getName() . '.extid');
	
	       if ($id) {
	
	           // Initialise the table
	           $table = $this->getTable();
	
	           // Attempt to check the row in.
	           if (method_exists($table, 'checkin')) {
	               if (!$table->checkin($id)) {
	                   $this->setError($table->getError());
	                   return false;
	               }
	           }
	       }
	
	       return true;
	   }
	/**
	     * Method to check out an item for editing.
	     *
	     * @param	integer		The id of the row to check out.
	     * @return	boolean		True on success, false on failure.
	     * @since	1.6
	*/
	    public function checkout($id = null) {
	        // Get the user id.
	        $id = (!empty($id)) ? $id : (int) $this->getState($this->getName() . '.extid');
	
	        if ($id) {
	
	            // Initialise the table
	            $table = $this->getTable();
	
	            // Get the current user object.
	            $user = JFactory::getUser();
	
	            // Attempt to check the row out.
	            if (method_exists($table, 'checkout')) {
	                if (!$table->checkout($user->get('id'), $id)) {
	                    $this->setError($table->getError());
	                    return false;
	                }
	            }
	        }
	
	        return true;
	    }
	public function getTable($type = 'Extension', $prefix = 'ExtporterTable', $config = array()) {
	        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
	        return JTable::getInstance($type, $prefix, $config);
	    }
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 * @generated
	 */
	public function getForm($data = array(), $loadData = true)
	{
		
		
	
		// Get the form.
		$form = $this->loadForm('com_extporter.extensionsdetails', 'extension', array('control' => 'jform', 'load_data' => $loadData));
	
		if (empty($form)) {
			return false;
		}
	
		return $form;
	}
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 * @generated
	 */
	public function getItem($pk = null)
	{
		$app	= JFactory::getApplication();
		$pk = (!empty($pk)) ? $pk : $app->input->getInt("extid");
	        $table = $this->getTable();
	
	        if ($pk > 0)
	        {
	            try{
	               // Attempt to load the row.
	               $return = $table->load($pk);
	           }catch (Exception $e){
	               // Check for a table object error.
	               throw new Exception('Database Failur:  no element Found'. $e . $return);
	           }
	        }
	
	       // Convert to the JObject before adding other data.
	        $properties = $table->getProperties(1);
	        $item =  ArrayHelper::toObject($properties);
	
	        if (property_exists($item, 'params'))
	        {
	            $registry = new Registry;
	            $registry->loadString($item->params);
	            $item->params = $registry->toArray();
	        }
	
		return $item;
	}
	
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 * @generated
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_extporter.edit.extension.data', array());
	
		if (empty($data)) {
			$data = $this->getItem();
		}
	
		return $data;
	}
	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
		public function save($data)
		{
			$id = (!empty($data['extid'])) ? $data['extid'] : (int)$this->getState($this->getName() . '.extid');
	        $state = (!empty($data['state'])) ? 1 : 0;
	        $user = JFactory::getUser();
	
	        if($id) {
	            //Check the user can edit this item
	            $authorised = $user->authorise('core.edit', 'com_extporter.extensionsdetails.'.$id) 
	            || $authorised = $user->authorise('core.edit.own', 'com_extporter.extensionsdetails.'.$id);
	            if($user->authorise('core.edit.state', 'com_extporter.extensionsdetails.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
	                $data['state'] = 0;
	            }
	        } else {
	            //Check the user can create new items in this section
	            $authorised = $user->authorise('core.create', 'com_extporter');
	            if($user->authorise('core.edit.state', 'com_extporter.extensionsdetails.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
	                $data['state'] = 0;
	            }
	        }
	
	        if ($authorised !== true) {
	            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
	            return false;
	        }
	        $inputs =& JFactory::getApplication()->input->get("jform", array(), 'array');
	        	
	        	
	        $table = $this->getTable();
	        if ($table->save($data) === true) {
	        	if(empty($inputs['extid']) || $inputs['extid'] == 0 )
	        		$inputs['patid']=$table->extid;
	            return $table->extid;
	        } else {
	            return false;
	        }
	        
		}
	
	/**
	* to Delete Data of a Item
	*@param Int $data   content the Id
	*
	*/
		 function delete($data)
	    {
	        $id = (!empty($data)) ? $data : (int)$this->getState($this->getName() . '.extid');
	        if(JFactory::getUser()->authorise('core.delete', 'com_extporter.extensionsdetails.'.$id) !== true){
	            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
	            return false;
	        }
	        $table = $this->getTable();
	        if ($table->delete($data) === true) {
	            return $id;
	        } else {
	            return false;
	        }
	        
	        return true;
	    }
	
}
