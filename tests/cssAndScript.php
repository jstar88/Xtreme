<?php
/**
 *A simple example of css and javascript usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');
Xtreme::addCssToGroup("myCss");
Xtreme::addScriptToGroup("myScript");
Xtreme::output('SimpleCssAndScript',false,true);
?>