<?php

/**
 * Created by PhpStorm.
 * User: dieudonne
 * Date: 17.04.2017
 * Time: 14:02
 */

class DBTableExporter
{
    public  $shemas = "";
    public  $reference = array();
    public  $columns = array();
    public  $name = "";
    public $realname="";
    public $extensionname = "";
    public $haveuser = false;
    public $havecategories = false;
    public $haveasset = false;
    public $havemenu = false;
    public $haveRef = false;
    public $isAlreadyShow= 0;
    public $dbName ="";
    public $dbPrefix="";
    public $existLive= true;

    public function __construct($tablename ="", $extensionname="")
    {
        $this->extensionname = $extensionname;
        $this->name = $tablename;


    }
    public  function initTable(){
        $this->dbName = JFactory::getApplication()->loadConfiguration("")->get("db");;
        $this->dbPrefix = JFactory::getDbo()->getPrefix();
        $this->realname = preg_replace("/#__/",$this->dbPrefix,$this->name);
        $this->searchReference();
        $this->searchColumns();
    }
    function setisAlreadyShow($tr=1){
        $this->isAlreadyShow = $tr;
    }
    function searchColumns(){
        try {
            $db = JFactory::getDbo();
            $query = "SHOW COLUMNS FROM $this->name";
            $db->setQuery($query);
            $data = $db->loadObjectList();
            $this->existLive = (count($data) == 0)? false : true;
            foreach ($data as $keys => $value) {
                $this->columns[] = $value->Field;
            }
        }catch ( RuntimeException $s){
            //print_r("Table not Found" . $s);
            $this->existLive = false;
        }
    }
    function searchReference(){
        try {
        $db =  JFactory::getDbo();

        $query ="select distinct referenced_table_name as reftable from information_schema.key_column_usage " .
            " where constraint_schema = '$this->dbName' and table_name = '$this->realname' and referenced_table_name is not  null";
        $db->setQuery($query);

        $data = $db->loadObjectList();
         $this->haveRef = (count($data) == 0)? false : true;

        foreach ($data as $key=>$value){
            $this->reference [] =  preg_replace("/".$this->dbPrefix."/", "#__", $value->reftable);
        }
       // print_r("<br /><br />". $this->name . " " . print_r($this->reference) . "\n<br /><br />");
        }catch ( RuntimeException $s){
           // print_r("Table not Found" . $s);
            $this->existLive = false;
        }
    }
    function getSchema(){
        $db = JFactory::getDbo();
        $query = "SHOW CREATE TABLE $this->name";
        $db->setQuery($query);
        $data = $db->loadObject();
        $shema = get_object_vars($data)['Create Table'];
        $temp = preg_replace("/CREATE TABLE/","CREATE TABLE IF NOT EXISTS",$shema);

        return preg_replace("/`".$this->dbPrefix."/","`#__",$temp).";";
    }

    function  getData(){
        $db =  JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")->from($this->name);
        $db->setQuery($query);
        $data= $db->loadObjectList();
        if(count($data)== 0)
            return "";
        $schemas ="INSERT INTO $this->name (" . implode(",",$this->columns).") \n VALUES";
        $count = count($data)-1;
        foreach ($data as $key=>$value){
            $row = "(";
            foreach ($this->columns as $column){
                $tv = $value->$column;
                if($column != end($this->columns))
                    if(intval($tv)){
                    $row.= "$tv" .',';}
                        else { $row.= "'$tv'" .',';}

                else
                    if(intval($tv)){
                        $row.= "$tv" .',';}
                    else { $row.= "'$tv'" .',';}
            }
            $row .= ")";
            if($key != $count ){
                $schemas.= $row . ",\n";
            }else{
                $schemas.= $row . ";\n";
            }
        }
        return $schemas . "\n";

    }


}


