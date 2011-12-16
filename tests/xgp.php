<?php
/**
 * xgp galaxy in one file
 * Only the architecture
 * */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../Xtreme.php');
Xtreme::init();
Xtreme::setBaseDirectory(dirname(__FILE__));
Xtreme::setCachesDirectory('tmp');
Xtreme::setTemplatesDirectory('templates/xgp');
Xtreme::setOnInexistenceTagEvent(Xtreme::SHOW_TAG);

Xtreme::assign('title','xgp galaxy');
$array=array();
for($i=1;$i<11;$i++){
    $array[]=array('position'=>$i);    
}
Xtreme::assign('rows',$array); 

Xtreme::assignToGroup('main_page','head','head');
Xtreme::assignToGroup('main_page','footer','footer');

//---------building the group Galaxy subgroup of Main_Page
Xtreme::assignToGroup('galaxy_body','script','galaxy/galaxy_script');
Xtreme::assignToGroup('galaxy_body','selector','galaxy/galaxy_selector');
Xtreme::assignToGroup('galaxy_body','titles','galaxy/galaxy_titles');
Xtreme::assignGroupToGroup('galaxy_body','galaxy/galaxy_body','main_page','body');
//-----------------------------

//---------building the group Row, subgroup of Galaxy 
Xtreme::assignToGroup('galaxy_row','row_position','galaxy/row/row_position');
Xtreme::assignToGroup('galaxy_row','row_planet','galaxy/row/row_planet');
Xtreme::assignToGroup('galaxy_row','row_planet_name','galaxy/row/row_planet_name');
Xtreme::assignToGroup('galaxy_row','row_moon','galaxy/row/row_moon');
Xtreme::assignToGroup('galaxy_row','row_debris','galaxy/row/row_debris');
Xtreme::assignToGroup('galaxy_row','row_user','galaxy/row/row_user');
Xtreme::assignToGroup('galaxy_row','row_ally','galaxy/row/row_ally');
Xtreme::assignToGroup('galaxy_row','row_actions','galaxy/row/row_actions');
Xtreme::assignGroupToGroup('galaxy_row','galaxy/row/row_body','galaxy_body','rows');
Xtreme::doLoopGroup('galaxy_body','rows','row');
//-----------------------------

$Page=Xtreme::outputGroup('main_page','page');
$Page.= '<br><br>*HDD accesses: '.Xtreme::getHddAccess();
echo $Page;
?>
