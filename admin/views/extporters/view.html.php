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

		
		
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Extporter View
 */
class ExtporterViewExtporters extends JViewLegacy
{

 /** Method to get display
 *
 * @param   Object  $tpl  template
 *
 * @return void
 * @generated
 */
    public function display($tpl = null)
    {
        if (!JFactory::getUser()->authorise('core.administrator'))
        {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        JHtml::_('behavior.tooltip');

        $document = JFactory::getDocument();

        JHtml::_('tabs.start');

        $application = JFactory::getApplication("administrator");
        $this->option = $application->scope;

        $this->addToolBar();

        $this->addViews();

        parent::display($tpl);
    }

/**
 * creates a joomla administratoristrative tool bar
 *
 * @return void
 * @generated
 */
    private function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_EXTPORTER') . ': ' . JText::_('COM_EXTPORTER_HOME'), 'logo');
        JToolBarHelper::preferences('com_extporter');
    }

/**
 * creates html elements for the main menu
 *
 * @return void
 * @generated
 */
    private function addViews()
    {
        $views = array();

	$views['extensionlist'] = array();
	$views['extensionlist']['title'] = JText::_('COM_EXTPORTER_TITLE_EXTENSIONLIST');
	$views['extensionlist']['url'] = "index.php?option=com_extporter&view=extensionlist";
      
$this->views = $views;
}
}
