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
class JFormFieldComponentuser extends JFormFieldList{
    
    protected function getOptions()
    {
    	$entity = $this->getAttribute('entity');
    	$table = "#__extporter_" . $entity;
    	$dbo = JFactory::getDbo();
    	$query = $dbo->getQuery(true);
    	$query->select("DISTINCT a.created_by AS value, b.name AS text")
    		->from("$table AS a ")
    		->leftJoin("#__users AS b ON a.created_by = b.id")
    		->order("b.name ASC");
    	$dbo->setQuery($query);
    	$dataList = $dbo->loadObjectList();
    	return  array_merge(parent::getOptions(),$dataList);
    }
}
