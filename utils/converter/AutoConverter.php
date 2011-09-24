<?php
include('Converter.php');
include('SystemUtil.php');
class AutoConverter extends Converter
{
   public function convertRecursively($directory,$sourceExt,$targetExt){
     parent::setTargetExtension($targetExt);
      parent::setSourceExtension($sourceExt);
      SystemUtil::listFile_recursively($directory,array($this,'listEvent'), $sourceExt);
   }
   public function listEvent($info){
      parent::setSourceFile($info['path'].$info['name']); 
      parent::setTargetFile($info['path'].$info['name']);
      parent::convert();     
   }
}
?>
