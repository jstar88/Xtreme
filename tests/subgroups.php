<?php
/**
 * advanced:groups usage
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates');

Xtreme::assign('title','an example of subgroups');
Xtreme::assignToGroup('x','head','subgroup/head_template');

//---------building the subgroup
Xtreme::assign('anotherKey','Hello World');
Xtreme::assignToGroup('y','akey','subgroup/field_template');
Xtreme::assignGroupToGroup('y','subgroup/body_group_template','x','body');
//-----------------------------

//echo Xtreme::outputGroup('y','subgroup/body_group_template');
echo Xtreme::outputGroup('x','subgroup/page_template');
echo '<br><br>*HDD accesses: '.Xtreme::getHddAccess();

	
?>                                                                                                                                                   