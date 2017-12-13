<?php

/**
 * Created by PhpStorm.
 * User: dieudonne
 * Date: 14.04.2017
 * Time: 23:42
 */
require_once dirname(__FILE__) . '/sql_parser/Parser.php';
require_once dirname(__FILE__) . '/dbtable.php';
class Extractor
{
    public $com_name ='';
    public $sqlinstallFiles = array();
    public $sqlFiledestination =array();
    public $sqlupdateFiles = array();
    public $sqlinstallPath = array();
    public $sqlupdatePath = array();
    public $site ='';
    public $admin ='';
    public $mediaPath="";
    public $destination="";
    public  $sqldestionation="";
    public  $sitedestionation="";
    public  $admindestionation="";
    public  $mediadestionation="";
    public $language = array();
    public  $rootPath ="";
    public  $dbAllTable = array();
    public  $dbAllTableName = array();
    public $name;
    public $destinationame;

    function fileExtractor($installSQLPathFiles, $rootPath){
        $loadscript = simplexml_load_file($installSQLPathFiles);
        $this->rootPath = $rootPath;
        //$type = $loadscript['@at']
        foreach ($loadscript->children() as $index => $val){

            switch ($index){
                case "name":

                    $name =strtolower(substr( $val, 0, 4 ));
                    if($name == "com_"){
                        $this->com_name = strtolower($val);
                        $this->name = strtolower(preg_replace("/com_/","",$val));
                    }
                    else {
                        $this->com_name = "com_" . strtolower($val);
                        $this->name= strtolower($val);
                    }
                    break;
                case "install":
                    $sqlTag = $val->sql;
                    foreach ($sqlTag->children() as $t => $b){
                        if(strcmp($b->attributes()['driver']->__toString(), 'mysql')
                            || strpos($b->__toString(),'mysql')!= false){
                            $this->sqlinstallFiles [] = $rootPath ."/administrator/components/" . $this->com_name . "/". $b->__toString();
                            $this->sqlFiledestination []  =  $b->__toString();
                        }
                    }


                    break;
                case "update":
                    $updateFolder = $rootPath . "/administrator/components/" . $this->com_name . "/" .$val->schemas->schemapath;
                    $updateFolderOrigin = $val->schemas->schemapath;
                    if(is_file($updateFolder)){
                        die("The Update Path must be a Folder");
                    }
                    $this->sqlupdateFiles = $this->scanAllFileInFolder($updateFolder,$this->sqlupdateFiles);
                    break;
                case "files":
                    $this->site = $rootPath . "/components/" . $this->com_name;
                    $siteDest = $val->attributes()['folder'];
                    break;
                case "administration":
                    $languagefolder="";
                    if(!empty($val->languages->attributes()['folder'])){
                        $languagefolder = $val->languages->attributes()['folder']."/";
                    }
                    $this->admin = $rootPath . "/administrator/components/" . $this->com_name;
                    foreach ($val->languages->children() as $lk => $lv){
                        $lcode = $lv->attributes()['tag']->__toString();
                        $this->language["admin"][$lcode][]= $languagefolder . $lv->__toString();
                    }
                    $adminDest = $val->files->attributes()['folder'];
                    break;
                case "media":
                    $this->mediaPath = $rootPath . "/media/".  $val->attributes()->destination->__toString();
                    break;
                case "languages":
                    $languagefolder="";
                    if(!empty($val->attributes()['folder'])){
                        $languagefolder = $val->attributes()['folder']."/";
                    }
                    foreach ($val->children() as $lk => $lv){
                        $lcode = $lv->attributes()['tag']->__toString();

                        $this->language["site"][$lcode][]= $languagefolder . $lv->__toString();

                    }
                    ;

            }

        }
        $date=new DateTime(); //this returns the current date time
        $result = $date->format('Y-m-d-H-i-s');
        $this->destination = $rootPath . "/media/com_extporter/export/" . $this->com_name . $result ;
        $this->destinationame = $this->com_name . $result;
        if(!empty($adminDest)){
            $this->admindestionation = $this->destination . "/". $adminDest;
            $this->sqldestionation = $this->destination . "/". $adminDest. "/sql";
        }else{
            $this->admindestionation = $this->destination . "/administrator/components/" . $this->com_name;
            $this->sqldestionation = $this->destination . "/administrator/components/" . $this->com_name . "/sql";
        }
        if (!empty($siteDest)){
            $this->sitedestionation = $this->destination . "/".$siteDest;
        }else{
            $this->sitedestionation = $this->destination . "/components/" . $this->com_name;
        }


        $this->mediadestionation =  $this->destination . "/media/" . $this->com_name;
        $this->createFolder($this->sitedestionation);
        $this->createFolder($this->admindestionation);
        $this->createFolder($this->mediadestionation);
        if(is_dir($this->site))
            $this->copy_folder($this->site,$this->sitedestionation);
        if(is_dir($this->admin))
            $this->copy_folder($this->admin,$this->admindestionation);
        if(is_dir($this->mediaPath)){
            $this->copy_folder($this->mediaPath,$this->mediadestionation);

        }
        if(is_file($this->admin ."/". $this->com_name .".xml")){
            copy($this->admin ."/". $this->com_name .".xml", $this->destination ."/". $this->com_name .".xml");
            unlink($this->admindestionation . "/" . $this->com_name . '.xml');
        }else{
            copy($this->admin ."/". $this->name .".xml", $this->destination ."/". $this->name .".xml");
            unlink($this->admindestionation . "/" . $this->name . '.xml');
        }

        if(is_file($this->admin ."/". "script.php")){
            copy($this->admin ."/". "script.php", $this->destination ."/" ."script.php");
            unlink($this->admindestionation . "/script.php");
        }
        $this->copyLanguageFiles();
        $instalsqlArray = explode("/",$this->sqlFiledestination[0]);
        array_pop($instalsqlArray);
        $this->createFolderStructure( $this->admindestionation,implode("/",$instalsqlArray));
        if(!empty($updateFolderOrigin))
            $this->createFolderStructure( $this->admindestionation,$updateFolderOrigin);

        $this->writeInstallSQL($this->admindestionation ."/".$this->sqlFiledestination[0]);
        array_shift($this->sqlFiledestination);
        foreach ($this->sqlFiledestination as $emptyfile){
            file_put_contents($this->sqldestionation ."/".$emptyfile,"");
        }

        return $loadscript;

    }
    function  copyLanguageFiles(){
        $languageAdminPath = $this->rootPath ."/administrator/language";
        $languageSitePath = $this->rootPath ."/language";
        foreach ($this->language as $lk=>$lv){
            if($lk == "site"){
                $root = $languageSitePath;
            }else{
                $root = $languageAdminPath;
            }
            foreach( $lv  as $lvk => $lvv){
                //var_dump($lvk, $lvv);
                foreach($lvv as $val){
                    $tpl = explode("/",$val);
                    $tplpop= array_pop($tpl);
                    $tmpPath= implode("/",$tpl);
                    $this->createFolderStructure($this->destination,$tmpPath);

                    $langFile = $root ."/".$lvk."/".$lvk .".". $this->com_name;
                    if(is_file($langFile . ".ini")){
                        $t = copy($langFile. ".ini",$this->destination ."/". $val);
                        // echo $t;
                    }
                    if(is_file($langFile . ".sys.ini")){
                        $t = copy($langFile. ".sys.ini",$this->destination ."/". $val);
                        // echo $t;
                    }
                }
            }
        }
    }
    function  createFolder($path){

        if (!mkdir($path, 0777, true)) {
            die('Erstellung der Verzeichnisse schlug fehl... ' . $path );
        }
    }
    function  createFolderStructure($root,$path){
        $patharray = explode("/",$path);
        $pa = "";
        foreach ($patharray as $folder){
            $pa .= "/" .$folder;
            // var_dump($pa);
            if(!is_dir($root. $pa)&& !is_file($root . $pa)){
                $this->createFolder($root . $pa);
            }
        }

    }
    function copy_folder($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file)  ) {
                    if( strcmp($file, "sql") !=0)
                        $this->copy_folder($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    function  databaseExtractor($installSQLPathFiles, $updateFolderPath, $extensionName){

        $allTable = array();
        $allTableName=array();
        $allTable = $this->readSQLFiles($extensionName,$installSQLPathFiles,$allTable,$allTableName);
        $tempAllTable = $this->readSQLFiles($extensionName,$updateFolderPath,$allTable,$allTableName);
        array_merge($allTable,$tempAllTable);
        foreach ($allTable as $tabsql){
            $tabsql->initTable();
        }
        $this->dbAllTable= $allTable;
        $this->dbAllTableName = $allTableName;

    }

    function  writeInstallSQL($pathTosqlFiles){
        $result ="";
        $this->databaseExtractor($this->sqlinstallFiles,$this->sqlupdateFiles,$this->com_name);

        foreach ($this->dbAllTable as $index=>$table){
            if(!$table->isAlreadyShow && $table->existLive){
                if($table->haveRef){
                    $result .= $this->readTableWithRef($table);
                }else{
                    $result .= $table->getSchema() ."\n";
                    $result .= $table->getData() . "\n";
                    $table->setisAlreadyShow(1);
                }
            }
        }
        file_put_contents($pathTosqlFiles,$result);
    }

    /**
     * @return string
     */
    public function readTableWithRef($table)
    {
        $result ="";
        if($table->isAlreadyShow >= 2){
            return "";
        }
        $table->setisAlreadyShow(2);
        $parenTable = $this->searParent($table->reference);
        foreach ($parenTable as $ref){
            $tmp =$this->dbAllTable[$ref];
            if($tmp->haveRef && $tmp->isAlreadyShow == 0){
                $result .= $this->readTableWithRef($tmp);
            }elseif(!$tmp->haveRef&& $tmp->isAlreadyShow==0){
                $result .= $tmp->getSchema() ."\n";
                $result .= $tmp->getData() . "\n";
                $tmp->setisAlreadyShow(1);
            }if($tmp->isAlreadyShow==2){

                $table->setisAlreadyShow(3);


            }
        }
        $result .= $table->getSchema() ."\n";
        $result .= $table->getData() . "\n";
        if($table->isAlreadyShow < 3){
            $table->setisAlreadyShow(1);
        }

        return $result;

    }
    public function searParent($tableArray)
    {
        $result = array();
        foreach ($this->dbAllTable as $k => $v){
            if(in_array($v->name,$tableArray)){
                $result[] = $k;
            }
        }
        return $result;
    }
    public function  readSQLFiles($extensionName,$installPathFiles,$allTable,$allTableName){

        foreach ($installPathFiles as $keys=>$file){
            $installPathFileOpen = fopen($file, "r") or die("Unable to open sql file! $file");
            $installContent = fread($installPathFileOpen,filesize($file));
            $installSql = new SQL_Parser($installContent,"MySQL");
            $installContentParse = $installSql->parseQuery();
            if(array_key_exists('create',$installContentParse)){
                foreach($installContentParse['create'] as $index => $value){
                    if(array_key_exists('CREATE',$value)){
                        $tmp = $value['TABLE']['name'];
                        // echo $tmp . "\n";
                        $allTableName[] = $tmp;
                        $allTable[] = new DBTableExporter($tmp,$extensionName);

                    }

                }
            }
            if(array_key_exists('rename',$installContentParse)){
                foreach($installContentParse['rename'] as $index => $value){
                    foreach ($value as $in => $val){
                        foreach ($val as $i => $j){
                            $tmpsrc = $j['source']['table'];
                            $tmpnew = $j['destination']['table'];
                            // echo "source ".$tmpsrc . "\n New Source ".$tmpnew. "\n";
                            foreach ($allTable as $idg => $dtTable){
                                if(strcmp($dtTable->name,$tmpsrc)==0){
                                    if(!in_array($tmpnew,$allTableName)){
                                        $dtTable->name = $tmpnew;
                                        $allTableName[$idg] = $tmpnew;
                                    }else{
                                        unset($allTable[$idg]);
                                        unset($allTableName[$idg]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose($installPathFileOpen);
        }
        return $allTable;
    }

    public function scanAllFileInFolder($folder,$folderList){
        $files1 = scandir($folder);
        foreach ($files1 as $keys=>$file){
            if(is_file($folder . "/" . $file)){
                if($file !="." && $file !=".." && $file != 'index.html' && file_exists($folder . "/" . $file)){
                    $new_file = $folder . "/" . $file;
                    $folderList[] = $new_file;
                    //print_r("Filename " . $new_file . "\n");
                }
            }else{
                if($file !="." && $file !="..")
                    $this->scanAllFileInFolder($folder . "/" . $file, $folderList);
            }
        }
        return $folderList;
    }
}

//print_r((new Extractor())->databaseExtractor(array('C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/install.mysql.utf8.sql'),array('C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.2.0.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.3.0.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.3.10.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.3.7.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.3.9.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/1.0.14.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/1.0.5.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/1.1.3.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/1.1.4.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/2.0.0.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/2.2.0.sql','C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/2.2.9.sql')
//,array(),'thm_organizer'));

//print_r((new Extractor())->fileExtractor("C:/Users/dieudonne/SkyDrive/Dokumente/code/admin/com_extporter/com_extporter.xml"));
//$ex = new Extractor();
//$ter = $ex->fileExtractor("C:/xampp/htdocs/mddjoomla/administrator/components/com_extporter/com_extporter.xml","C:/xampp/htdocs/mddjoomla");
//$ter = $ex->fileExtractor("C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/com_thm_organizer.xml","C:/xampp/htdocs/mddjoomla");
//$ex->scanAllFileInFolder("C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates", array());
//$ter = $ex->fileExtractor("C:/Users/dieudonne/Desktop/Arbeit 2017/pkg_rcpusermanager_1.0.2/com_rcpusermanager","C:/xampp/htdocs/mddjoomla");

//print_r($ex);