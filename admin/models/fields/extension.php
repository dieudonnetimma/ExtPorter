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

		
		

JFormHelper::loadFieldClass('list');

class JFormFieldextension extends JFormFieldList{
    protected $table = "#__extporter_extension";
    
    protected function getOptions()
    {
     	$valueColumn = $this->getAttribute('valueColumn');
     	$textColumn = $this->getAttribute('textColumn');
     	return  array_merge(parent::getOptions(),$this->getAllData($valueColumn, $textColumn));
    }
    
    protected function getAllData($valueColumn, $textColumn)
    {
    	$dbo = JFactory::getDbo();
    	$query = $dbo->getQuery(true);
    	$query->select("DISTINCT $valueColumn as value, $textColumn as text")
    		->from("$this->table AS extension")
    		->order("$textColumn ASC");
    	$dbo->setQuery($query);
    	$result = $dbo->loadObjectList();
    	return $result;
    }
 }
