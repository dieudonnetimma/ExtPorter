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

		
		

// import Joomla table library
jimport('joomla.database.table');

/**
* Extension Table class
*/
class ExtporterTableExtension extends JTable
{
	public $foreigntableOption = array();
	
	/**
	* Constructor
	*
	* @param object Database connector object
	*/
	function __construct(&$db) 
	{
		parent::__construct('#__extporter_extension', 'extid', $db);
		$this->initTheForeignTableOption();
	}
	
	/**
	* Overloaded bind function to pre-process the params.
	*
	* @param    array        Named array
	*
	* @return    null|string    null is operation was satisfactory, otherwise returns an error
	* @see        JTable:bind
	* @since      1.5
	*/
	public function bind($array, $ignore = '')
	{
		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');
		if(($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state','com_extporter.extension.'.$array['id']) && $array['state'] == 1))
		{
			$array['state'] = 0;
		}
		if($array['extid'] == 0)
		{
			$array['created_by'] = JFactory::getUser()->id;
		}
		
		//Support for file field: file
		$input = JFactory::getApplication()->input;
		
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}
		
		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
	
		//Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}
	
		return parent::bind($array, $ignore);
	}
	
	/**
	* Rewrite check function
	*/
	public function check()
	{
		if (property_exists($this, 'ordering') && $this->extid == 0)
		{
			$this->ordering = self::getNextOrder();
		}
	
		return parent::check();
	}
	
	public function reset()
	{
		$this->extid = 0;
		parent::reset();
	}
	
	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 * @return string The asset name
	 *
	 * @see JTable::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_extporter.extension.' . (int) $this->$k;
	}
	
	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @see JTable::_getAssetParentId
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance('Asset');
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();
		// The item has the component as asset-parent
		$assetParent->loadByName('com_extporter');
		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}
		return $assetParentId;
	}
	
	public function  initTheForeignTableOption()
	{
	}
	
	public function loadAllPrimaryKeyofRef($pk, $keylist, $foreigntable, $foreignkeys,$foreignId)
	{   	
		$this->load($pk);
		$query = $this->_db->getQuery(true);
		$query->select($foreignId)
			->from("#__" . $foreigntable);
		foreach($keylist as $index=>$value)
		{
			$query->where($this->_db->quoteName($foreignkeys[$index]) . "=" .
			$this->_db->quoteName($this->$value));
		}
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		return $result;
	}
	
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_keys;
		if (!is_null($pks))
		{
			foreach ($pks AS $key => $pk)
			{
				if (!is_array($pk))
				{
					$pks[$key] = array($this->_tbl_key => $pk);
				}
			}
		}
	
		$userId = (int) $userId;
		$state  = (int) $state;
		
		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			$pk = array();
			foreach ($this->_tbl_keys AS $key)
			{
				if ($this->$key)
				{
					$pk[$this->$key] = $this->$key;
				}
				// We don't have a full primary key - return false
				else
				{
					return false;
				}
			}
			$pks = array($pk);
		}
		
		foreach ($pks AS $pk) 
		{
			// Update the state state for rows with the given primary keys.
			$query = $this->_db->getQuery(true)
				->update($this->_tbl)
				->set('state = ' . (int)$state);
			$this->appendPrimaryKeys($query, $pk);
			
			$this->_db->setQuery($query);
			$this->_db->execute();
			}
		return parent::publish($pks, $state, $userId);
	}
	
}
