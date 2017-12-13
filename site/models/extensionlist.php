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


jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Data.
 * @generated
 */
class ExtporterModelExtensionlist extends JModelList {
	
	private  $entitiesRef = array(
	null);
	/**
	* Constructor.
	*
	* @param    array    An optional associative array of configuration settings.
	* @see        JController
	* @since    1.6
	* @generated
	*/
	    public function __construct($config = array()) {
	        if (empty($config['filter_fields'])) {
	            $config['filter_fields'] = array(
	                                'extid', 'extension.extid',
	                'ordering', 'extension.ordering',
	                'state', 'extension.state',
	                'created_by', 'extension.created_by'
	                , 'published', 'extension.published'
	                ,'extid', 'extension.extid'
	                ,'title', 'extension.title'
	                ,'type', 'extension.type'
	                );}
	                parent::__construct($config);
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
		 public function getItems() {
	        $items = parent::getItems();
	        
	        return $items;
	    }
	/**
	   * Method to auto-populate the model state.
	   *
	   * Note. Calling getState in this method will result in recursion.
	   */
	  protected function populateState($ordering = 'extension.extid', $direction = 'asc') {
	      
	      // Load the parameters.
	      $params = JComponentHelper::getParams('com_extporter');
	      $this->setState('params', $params);
	
	      // List state information.
	      parent::populateState($ordering, $direction);
	  }
	/**
	 * Build an sql_parser query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 * @generated
	 */
	    protected function getListQuery() {
	        // Create a new query object.
	        $db = $this->getDbo();
	        $query = $db->getQuery(true);
	
	        // Select the required fields from the table.
	        $query->select(
	                $this->getState(
	                        'list.select', 'extension.*'
	                )
	        );
	        $query->from('`#__extporter_extension` AS extension');
	        // Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=extension.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = extension.created_by');
		// Join over the user field 'user'
		$query->select('user.name AS user');
		$query->join('LEFT', '#__users AS user ON user.id =  extension.created_by');
		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('extension.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(extension.state IN (0, 1))');
		}
		// Filter by User 
		$created_by = $this->getState('filter.created_by');
		if (!empty($created_by)) {
	            $query->where("extension.created_by = '$created_by'");
	            }
	        // Filter by extid 
	$extid = $this->getState('filter.extid');
	if (!empty($extid)) {
	            $query->where("extension.extid = '$extid'");
	            }
	        // Filter by title 
	$title = $this->getState('filter.title');
	if (!empty($title)) {
	            $query->where("extension.title = '$title'");
	            }
	        // Filter by type 
	$type = $this->getState('filter.type');
	if (!empty($type)) {
	            $query->where("extension.type = '$type'");
	            }
		// Filter by search in attribute
	        $search = $this->getState('filter.search');
	        if (!empty($search)) {
	            if (stripos($search, 'extid:') === 0) {
	                $query->where('extension.extid = ' . (int) substr($search, 3));
	            } else {
	                $search = $db->Quote('%' . $db->escape($search, true) . '%');
	                $query->where('( extension.extid LIKE '.$search. 
	                 
	                 ')');   
	            }}
	        // Add the list ordering clause.
	        $orderCol = $this->state->get('list.ordering');
	        $orderDirn = $this->state->get('list.direction');
	        if ($orderCol && $orderDirn) {
	            $query->order($db->escape($orderCol . ' ' . $orderDirn));
	        }
	
	        return $query;
	    }
	            
	/**
	   * Method to get the id of Reference
	   * @param String  $linkName   containt the name of a Attribute
	   * @param String  $attrvaluen  containt the value of a Row
	   *
	   * @return the ID of a Row
	   *
	   */
	 public function getIdOfReferenceItem($linkName, $attrvalue){
	    $dbtable = $this->entitiesRef["$linkName"]["db"];
	    $attribute = $this->entitiesRef["$linkName"]["refattr"];
	    $db = JFactory::getDbo();
	    $query = $db->getQuery(true);
	     $key = $this->entitiesRef["$linkName"]["foreignPk"];
	        $query->select($key)
	        ->from($dbtable);
	     foreach ($attribute as $index=>$attributItem){ 
	        $query->where($attributItem . " like '".$attrvalue->$index."'");
	     }
	    $db->setQuery($query);
	    $result = $db->loadObject();
	
	    return intval($result->$key);
	  }
}

