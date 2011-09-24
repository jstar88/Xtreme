<?php
class Converter
{
    private $root;
    private $sourceFile;
    private $targetFile;
    private $sourceExt;
    private $targetExt;
    private $php_var;
    
    public function __construct($sourceFile='',$targetFile='',$sourceExt='mo',$targetExt='json',$php_var='lang')
    {
        $this->root=dirname(__FILE__);
        $this->sourceFile=$sourceFile;
        $this->targetFile=$targetFile; 
        $this->sourceExt=$sourceExt; 
        $this->targetExt=$targetExt; 
        $this->php_var=$php_var; 
    }
    public function set_phpVar($new){
        $this->php_var=$new; 
    }
    public function setSourceFile($sourceFile){
        $this->sourceFile=$sourceFile;    
    }
    public function setTargetFile($targetFile){
        $this->targetFile=$targetFile;    
    }
    public function setSourceExtension($sourceExt){
        $this->sourceExt=$sourceExt;    
    }
    public function setTargetExtension($targetExt){
        $this->targetExt=$targetExt;    
    }
    public function convert(){
        $funcOpen='open_'.$this->sourceExt;
        $langArray=$this->{$funcOpen}();
        $funcSave='save_'.$this->targetExt;  
        $this->{$funcSave}($langArray);          
    }
    
    private function open_mo()
    {
        require($this->getSourcePath());
        return ${$this->php_var};           
    }
    private function open_json()
    {
        return json_decode(file_get_contents($this->getSourcePath()),true);           
    }
    private function open_xml()
    {
        return simplexml_load_file($this->getSourcePath());       
    }
    private function open_ini()
    {
        if(function_exists('parse_ini_string'))
            return parse_ini_string(file_get_contents($this->getSourcePath()),true);
        else
            return parse_ini_file($this->getSourcePath(),true);            
    } 
    private function save_php($value)
    {
        //not implemented          
    }
    private function save_json($value)
    {
        file_put_contents($this->getTargetPath(),json_encode($value));           
    }
    private function save_xml($value)
    {
        $value->asXML($this->getTargetPath());    
    }
    
    private function getSourcePath(){
      return $this->root.'/'.$this->sourceFile.'.'.$this->sourceExt;
    }
    private function getTargetPath(){
      return $this->root.'/'.$this->targetFile.'.'.$this->targetExt;
    }
 }

?>