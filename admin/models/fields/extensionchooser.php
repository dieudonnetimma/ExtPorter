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

class JFormFieldExtensionchooser extends JFormFieldList{
    protected function getOptions()
    {

        return  array_merge(parent::getOptions(),$this->getAllData());
    }

    protected function getAllData()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->select("DISTINCT  extension_id as value,  name as text")
            ->from("#__extensions")
        ->where("type like 'component'")
            ->order("name ASC");
        $dbo->setQuery($query);
        $result = $dbo->loadObjectList();
        return $result;
    }
}