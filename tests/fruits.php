<?php
/**
 *A simple example of array usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
$xtreme=new Xtreme();
$xtreme->setBaseDirectory(dirname(__FILE__));
$xtreme->setCompileDirectory('tmp');
$xtreme->setTemplateDirectories('templates');

$array=array('key1'=>'apple','key2'=>'pear');
$array2=array('key3'=>'strawberry','key4'=>'plum');
$xtreme->assign($array);
$xtreme->assign('key',$array2);
$html=$xtreme->output('fruits');
echo $html;
?>
