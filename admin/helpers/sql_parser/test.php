<?php

require_once  dirname(__FILE__) . '/Parser.php';
$path= "C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql/0.3.7.sql";
$myfile = fopen($path, "r") or die("Unable to open file!");
$red = fread($myfile,filesize($path));
$test = new SQL_Parser($red,"MySQL");
$bra = $test->parseQuery();
print_r($bra);
foreach ($bra as $jjj=>$vv){
    print_r($jjj . " ");
    print_r($vv);
}
fclose($myfile);

$folder ="C:/xampp/htdocs/mddjoomla/administrator/components/com_thm_organizer/sql/updates/mysql";
$files1 = scandir($folder);
foreach ($files1 as $keys=>$file){
    if($file !="." && $file !=".." && $file != 'index.html' && file_exists($folder . "/" . $file)){
        $new_file = $folder . "/" . $file;
        print_r("Filename " . $new_file . "\n");
        $myfile2 = fopen($new_file, "r") or die("Unable to open sql file!");;
        $red = fread($myfile2,filesize($new_file));
        $test = new SQL_Parser($red,"MySQL");
        $bra = $test->parseQuery();
        foreach ($bra as $jjj=>$vv){
            print_r($jjj);
            print_r($vv);
        }
        fclose($myfile2);
    }
}


echo "hallo tata";


