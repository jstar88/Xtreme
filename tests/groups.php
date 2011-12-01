<?php
/**
 * advanced:groups usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');

Xtreme::assign('title','html page');
Xtreme::assignToGroup('x','head','group/head_template');

Xtreme::assign('title1','name');
Xtreme::assign('title2','age');
Xtreme::assign('value1','Frank');
Xtreme::assign('value2','28');
Xtreme::assignToGroup('x','body','group/body_template');

echo Xtreme::outputGroup('x','group/page_template');


?>
