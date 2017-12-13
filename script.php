<?php
 	    
 /**
 * @version 1.0.0
 * @category Joomla component
 * @package     Joomla.Administrator
 * @subpackage  com_extporter
 * @name extporterView
  * @author Dieudonne Timma   <dieudonne.timma.meyatchie@mni.thm.de> 
 * @copyright GNU 3
 * @license Open Source
 */
 defined('_JEXEC') or die('Restricted access');
 
  
/**
* com_extporter  script.
*/
class Com_extporterInstallerScript
{
  
  /**
  	 * method to install the component
  	 *
  	 * @return void
  	 */
  	function install($parent) 
  	{
  		if(!$this->setComponentParameter()){
  		  echo '<p>' .JText::_('COM_EXTPORTER_INSTALL_NO_PARAMETER_INSTALLED') . '</p>';
  		}
  		$parent->getParent()->setRedirectURL('index.php?option=com_extporter');
  	}
  
  
  
  function  setComponentParameter(){
  // Load the current component params.
  	 $params = JComponentHelper::getParams('com_extporter');
  // Set new value of param(s)
  $params->set('upload_maxsize', 10);
  $params->set('accept_format', "bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS");
  
  // Save the parameters
    $componentid = JComponentHelper::getComponent('com_extporter')->id;
    $table = JTable::getInstance('extension');
    $table->load($componentid);
    $table->bind(array('params' => $params->toString()));
  
  // check for error
    if (!$table->check()) {
  	  return false;
    }
  // Save to database
    if (!$table->store()) {
  	  return false;
    }
    return true;
  }
  /**
   * method to uninstall the component
   *
   * @return void
   */
  function uninstall($parent) 
  {
  	echo '<p>' .JText::_('COM_EXTPORTER_UNINSTALL_TEXT') . '</p>';
  }
   
  /**
   * method to update the component
   *
   * @return void
   */
  function update($parent) 
  {
  	
  	echo '<p>' . JText::sprintf('COM_EXTPORTER_UPDATE_TEXT',  $parent->get('manifest')->version) . '</p>';
  }
  /**
  	 * method to run before an install/update/uninstall method
  	 *
  	 * @return void
  	 */
  	/**function preflight($type, $parent) 
  	{
  		// $parent is the class calling this method
  		// $type is the type of change (install, update or discover_install)
  		echo '<p>' . JText::_('COM_EXTPORTER_PREFLIGHT_' . $type . '_TEXT') . '</p>';
  	}*/
  /**
  	 * method to run after an install/update/uninstall method
  	 *
  	 * @return void
  	 */
  	/**function postflight($type, $parent) 
  	{
  		// $parent is the class calling this method
  		// $type is the type of change (install, update or discover_install)
  		echo '<p>' . JText::_('COM_EXTPORTERPOSTFLIGHT_' . $type . '_TEXT') . '</p>';
  	}*/ 
  
}
