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
	 * Build an sql_parser query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 * @generated
	 */
	    protected function getListQuery() {
	        // Create a new query object.
	        $db = $this->getDbo();
            $bla = "select '#__ebay_customer' 
from information_schema.referential_constraints";
            $test = $db->setQuery($bla);
             $tata = $test->loadObjectList();

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
	* Function to save the new Order of the Profile
	*
	* @param   Array  $datas_ID  content the ID in the new Ordering
	*
	* @return array including headers
	* @generated
	*/
	    public function saveOrdering($datas_ID)
	    {
	        $db = JFactory::getDbo();
	        $query = $db->getQuery(true);
	
	        $statement = 'Update #__extporter_extension Set `ordering` = CASE';
	        foreach ($datas_ID  as $order => $profileID)
	        {
	            $statement .= ' WHEN extid = ' . intval($profileID) . ' THEN ' . (intval($order) + 1);
	        }
	        $statement .= ' ELSE ' . 0 . ' END Where extid IN(' . implode(',', $datas_ID) . ')';
	        $db->setQuery($statement);
	        $response = $db->execute();
	
	        if ($response)
	        {
	            $query = $db->getQuery(true);
	            $query->select('`extid`, `ordering`')->from('#__extporter_extension');
	            $db->setQuery($query);
	            return $db->loadObjectList();
	        }
	        return false;
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
}

