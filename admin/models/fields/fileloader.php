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

		
		

/**
 * This class contain a input field to load a document or image.
 * The parameter for configuration of the path, type, or format are
 * in the manifest file.
 */
class JFormFieldFileloader extends JFormField
{
    protected function getInput()
    {
        $html = array();
        $params = JComponentHelper::getParams('com_extporter');
        $path = $params->get($this->getAttribute('path'));
        $format = $params->get($this->getAttribute('accept_format'));
        $file='';
        if(!empty($this->value))
        {
            $file=  JURI::root()  .$path . '/'. $this->value;
        }

        $document = JFactory::getDocument();
        $iconpath = JURI::root() . 'media/media/images/mime-icon-32/';
        $document->addScript( JURI::root() . '/media/com_extporter/js/bootsnip.js');
        $document->addStyleSheet( JURI::root() . '/media/com_extporter/css/bootsnip.css');
        $document->addStyleSheet( JURI::root() . 'media/jui/css/bootstrap.min.css');
        $html []="<div class='img-picker' fieldtype='file' name='$this->name' accept='$format' file='$file' iconpath='$iconpath' showLabel='".JText::_("COM_EXTPORTER_ADD")."' 
        deleteLabel='".JText::_("COM_EXTPORTER_DELETE")."'><div id='add'></div><div id='preview'></div></div>";
        return implode($html);
    }
}
