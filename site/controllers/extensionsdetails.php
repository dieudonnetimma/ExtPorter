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


require_once JPATH_COMPONENT . '/controller.php';

/**
 * Extensionsdetails controller class to Show a Item .
 */
class ExtporterControllerExtensionsdetails extends ExtporterController {
	/**
	*
	*Delete a Item
	*
	*/
		public function remove() {
		
		        // Initialise variables.
		        $app = JFactory::getApplication();
		
		        //Checking if the user can remove object
		        $user = JFactory::getUser();
		        if ($user->authorise($user->authorise('core.delete', 'com_extporter'))) {
		            $model = $this->getModel('Extensionsdetails', 'ExtporterModel');
		
		            // Get the user data.
		            $id = $app->input->getInt('extid', 0);
		
		            // Attempt to delete the data.
		            $return = $model->delete($id);
		
		
		            // Check for errors.
		            if ($return === false) {
		                $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
		            } else {
		                // Check in the profile.
		                if ($return) {
		                    $model->checkin($return);
		                }
		
		                // Clear the profile id from the session.
		                $app->setUserState('com_extporter.edit.extensionsdetails.extid', null);
		
		                // Flush the data from the session.
		                $app->setUserState('com_extporter.edit.extensionsdetails.data', null);
		
		                $this->setMessage(JText::_('COM_EXTPORTER_ITEM_DELETED_SUCCESSFULLY'));
		            }
		
		            // Redirect to the list screen.
		            $menu = & JSite::getMenu();
		            $item = $menu->getActive();
		            $this->setRedirect(JRoute::_($item->link, false));
		        } else {
		            throw new Exception(500);
		        }
		}
	}
