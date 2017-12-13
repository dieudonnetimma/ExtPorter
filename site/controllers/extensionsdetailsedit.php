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
 * Extensionsdetails controller class to Edit a Item .
 */
class ExtporterControllerExtensionsdetailsEdit extends ExtporterController {
	/**
	* Method to save the data.
	*
	* @return	void
	* @since	1.6
	*/
	    public function save() {
	        // Check for request forgeries.
	        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
	        // Initialise variables.
	        $app = JFactory::getApplication();
		        $model = $this->getModel('ExtensionsdetailsEdit', 'ExtporterModel');
	
	        // Get the user data.
	        $data = JFactory::getApplication()->input->get('jform', array(), 'array');
	
	        // Validate the posted data.
	        $form = $model->getForm();
	        if (!$form) {
	            JError::raiseError(500, $model->getError());
	            return false;
	        }
	
	        // Validate the posted data.
	        $data = $model->validate($form, $data);
	
	        // Check for errors.
	        if ($data === false) {
	            // Get the validation messages.
	            $errors = $model->getErrors();
	
	            // Push up to three validation messages out to the user.
	            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
	                if ($errors[$i] instanceof Exception) {
	                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
	                } else {
	                    $app->enqueueMessage($errors[$i], 'warning');
	                }
	            }
	
	            $input = $app->input;
	            $jform = $input->get('jform', array(), 'ARRAY');
	
	            // Save the data in the session.
	            $app->setUserState('com_extporter.edit.extensionsdetails.data', $jform, array());
	
	            // Redirect back to the edit screen.
	            $id = (int) $app->getUserState('com_extporter.edit.extensionsdetails.extid');
	            $this->setRedirect(JRoute::_('index.php?option=com_extporter&view=extensionsdetailsedit&layout=edit&extid=' . $id));
	            return false;
	        }
	
	        // Attempt to save the data.
	        $return = $model->save($data);
	
	        // Check for errors.
	        if ($return === false) {
	            // Save the data in the session.
	            $app->setUserState('com_extporter.edit.extensionsdetails.data', $data);
	
	            // Redirect back to the edit screen.
	            $id = (int) $app->getUserState('com_extporter.edit.extensionsdetails.extid');
	            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
	            $this->setRedirect(JRoute::_('index.php?option=com_extporter&view=extensionsdetailsedit&layout=edit&extid=' . $id, false));
	            return false;
	        }
	
	
	        // Check in the profile.
	        if ($return) {
	            $model->checkin($return);
	        }
	
	        // Clear the profile id from the session.
	        $app->setUserState('com_extporter.edit.extensionsdetails.extid', null);
	
	        // Redirect to the list screen.
	        $this->setMessage(JText::_('COM_EXTPORTER_ITEM_SAVED_SUCCESSFULLY'));
	        $menu = JFactory::getApplication()->getMenu();
	        $item = $menu->getActive();
	        $url = (empty($item->link) ? 'index.php?' : $item->link);
	        $this->setRedirect(JRoute::_($url, false));
	
	        // Flush the data from the session.
	        $app->setUserState('com_extporter.edit.extensionsdetails.data', null);
	    }
	
	/**
	* To cancel the Edit of a Item
	*
	*/
		function cancel() {
		
		        $app = JFactory::getApplication();
		
		        // Get the current edit id.
		        $editId = (int) $app->getUserState('com_extporter.edit.extensionsdetails.extid');
		
		        // Get the model.
		       $model = $this->getModel('ExtensionsdetailsEdit', 'ExtporterModel');
	
		
		        // Check in the item
		        if ($editId) {
		            $model->checkin($editId);
		        }
		        
		        $menu = JFactory::getApplication()->getMenu();
		        $item = $menu->getActive();
		        $url = (empty($item->link) ? 'index.php?' : $item->link);
		        $this->setRedirect(JRoute::_($url, false));
	    }
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
