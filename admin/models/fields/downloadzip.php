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

class JFormFieldDownloadzip extends JFormFieldList{
    protected function getInput()
    {
        $html = "";
        $model = $this->form->getData()->get("model");
        $zipPath= JPATH_ROOT . "/media/com_extporter/export/" . $model.".zip";
        if(is_file($zipPath)){
            return  JHtml::link(JUri::root(true) ."/media/com_extporter/export/" . $model .".zip", $model);
        }
        return $html;
    }
}