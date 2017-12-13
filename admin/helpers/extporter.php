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

		
		
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
  /**
     * EXTPORTER  helper.
     */
    class ExtporterHelper {
     
     /**
      * Configure the Linkbar.
      */
         public static function addSubmenu($vName = '') {
     	
     		JHtmlSidebar::addEntry(
     		
     		JText::_('COM_EXTPORTER_TITLE_EXTENSIONLIST'),
     		'index.php?option=com_extporter&view=extensionlist',
     		$vName == 'extensionlist'
     		);
     	     		
     	  
     
          }
     /**
              * Gets a list of the actions that can be performed.
              *
              * @return	JObject
              * @since	1.6
              */
             public static function getActions() {
                 $user = JFactory::getUser();
                 $result = new JObject;
         
                 $assetName = 'com_extporter';
         
                 $actions = array(
                     'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
                 );
         
                 foreach ($actions as $action) {
                     $result->set($action, $user->authorise($action, $assetName));
                 }
         
                 return $result;
             }
         
     /**
     	 * Save a file in Server
     	 * @param  $file    Array   contains the informtion of a File to upload
     	 * @param  $target  String  contains the path of Directory
     	 * @param  $oldName  String  contains the name of the old file
     	 * @return	boolean or String
     	 */
     	public static function uploadFiles($file, $target, $oldname) {
     		$file['name'] = JFile::makeSafe($file['name']);
     		$file['name'] = str_replace(' ', '_', $file['name']);
     		$file['filepath'] = JPath::clean(implode(DIRECTORY_SEPARATOR, array(JPATH_ROOT, $target, $file['name'])));
     		if (JFile::exists($file['filepath'])) {
     			$index =1;
     			$file["name"] = $index."_".$file["name"];
     			$file['filepath'] = JPath::clean(implode(DIRECTORY_SEPARATOR, array(JPATH_ROOT, $target, $file['name'])));
     			while(JFile::exists($file['filepath'])){
     				$index =     $index +1;
     				$file["name"] = $index."_".$file["name"];
     				$file['filepath'] = JPath::clean(implode(DIRECTORY_SEPARATOR, array(JPATH_ROOT, $target, $file['name'])));
     
     			}
     
     		}
     	
     		$object_file = new JObject($file);
     
     		if (!JFile::upload($object_file->tmp_name, $object_file->filepath))
     		{
     			return false;
     		}
     		if(!empty($oldname)){
     			$pathOfold = JPath::clean(implode(DIRECTORY_SEPARATOR, array(JPATH_ROOT, $target, $oldname)));
     			if(JFile::exists($pathOfold)){
     						JFile::delete($pathOfold);	
     					}
     
     		}
     		return $file['name'];
     
     	}

        public static  function createZipFile($destination,$compname){
            $zipFileName= $destination .".zip";
            $zip = new ZipArchive();
            $zip->open($zipFileName,ZIPARCHIVE::CREATE);
            ExtporterHelper::putInArchive($destination,$zip,$compname);
            $zip->close();
            return $zipFileName;

        }

        public static function putInArchive($folderPath,$archiv,$compname){
            if(is_file($folderPath)){
                $archiv->addFile($folderPath);
            }
            if(is_dir($folderPath)){
                $files = JFolder::files($folderPath);

                foreach ($files as $key =>$value){
                    $archiv->addFile($folderPath . "/" . $value, $compname. $value);
                }
                $folders = JFolder::folders($folderPath);
                foreach ($folders as $keyf =>$valuef){
                    ExtporterHelper::putInArchive($folderPath . "/" . $valuef,$archiv ,$compname . $valuef ."/");
                }
            }
        }

   	  }
