<?php
/**
 * advanced:groups usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
$xtreme=new Xtreme();
$xtreme->setCompileDirectory('tmp/');
$xtreme->setTemplateDirectories('templates/');

$xtreme->assign('title','html page');
$xtreme->assignToGroup('x','head','group/head_template');

$xtreme->assign('title1','name');
$xtreme->assign('title2','age');
$xtreme->assign('value1','Frank');
$xtreme->assign('value2','28');
$xtreme->assignToGroup('x','body','group/body_template');

echo $xtreme->outputGroup('x','group/page_html');


?>
