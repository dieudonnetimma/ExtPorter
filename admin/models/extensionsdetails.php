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


jimport('joomla.application.component.modeladmin');
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

include_once JPATH_ROOT . "/administrator/components/com_extporter/helpers/extractor.php";
include_once JPATH_ROOT . "/administrator/components/com_extporter/helpers/extporter.php";
/**
 * The Model To schow the Details of a Extensionsdetails  
 */
class ExtporterModelExtensionsdetails extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_extporter';
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Extension', $prefix = 'ExtporterTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 * @generated
	 */
	public function getForm($data = array(), $loadData = true)
	{
		
		
	
		// Get the form.
		$form = $this->loadForm('com_extporter.extensionsdetails', 'extension', array('control' => 'jform', 'load_data' => $loadData));
	
		if (empty($form)) {
			return false;
		}
	
		return $form;
	}
	
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 * @generated
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_extporter.edit.extension.data', array());
	
		if (empty($data)) {
			$data = $this->getItem();
		}
	
		return $data;
	}
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 * @generated
	 */
	public function getItem($pk = null)
	{
		$app	= JFactory::getApplication();
		$pk = (!empty($pk)) ? $pk : $app->input->getInt("extid");
	        $table = $this->getTable();
	
	        if ($pk > 0)
	        {
	            try{
	               // Attempt to load the row.
	               $return = $table->load($pk);
	           }catch (Exception $e){
	               // Check for a table object error.
	               throw new Exception('Database Failur:  no element Found'. $e );
	           }
	        }
	
	       // Convert to the JObject before adding other data.
	        $properties = $table->getProperties(1);
	        $item =  ArrayHelper::toObject($properties);
	
	        if (property_exists($item, 'params'))
	        {
	            $registry = new Registry;
	            $registry->loadString($item->params);
	            $item->params = $registry->toArray();
	        }
	
		return $item;
	}
	public  function  save($data){
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->select("*")
            ->from("#__extensions")
            ->where("extension_id like ". $data['extname']);
        $dbo->setQuery($query);
        $result = $dbo->loadObject();
        $dest_dir = JPATH_ROOT . "/media/com_extporter/export/" .  $data["model"];
        if(is_dir($dest_dir)  &&  !empty($data['extid']) && $data['extid'] != 0){
            JFolder::delete($dest_dir);
            JFile::delete($dest_dir .".zip");
        }
        $ex = new Extractor();
        $pathExt = JPATH_ADMINISTRATOR . "/components/$result->element/$result->element.xml";

        if(!file_exists($pathExt) ){
            $nameelement = explode("_",$result->element)[1];
            $pathExt = JPATH_ADMINISTRATOR . "/components/$result->element/" . $nameelement .".xml";

        }
         $ex->fileExtractor($pathExt,JPATH_ROOT);
         $data["model"] = $ex->destinationame;
        $zipDestination = JPATH_ROOT . "/media/com_extporter/export/" . $ex->destinationame;
        ExtporterHelper::createZipFile($zipDestination);
        return parent::save($data);
    }
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
	
		if (empty($table->extid)) {
	
			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__extporter_extension');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
	
		}
	}
}
