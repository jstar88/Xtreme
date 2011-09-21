<?php
class Converter
{
    private $sourceFile;
    private $targetFile;
    
    public function __construct($sourceFile='',$targetFile=''){
        $this->sourceFile=$sourceFile;
        $this->targetFile=$targetFile;    
    }
    public function setSourceFile($sourceFile){
        $this->sourceFile=$sourceFile;    
    }
    public function setDestinationFile($targetFile){
        $this->targetFile=$targetFile;    
    }
    public function convert(){
        $source=explode('.',$this->sourceFile);
        $funcOpen='open_'.$source[count($source)-1];
        $langArray=$this->{$funcOpen}();
        $funcSave='save_'.$targetExt;  
        $this->{$funcSave}($langArray);          
    }
    
    private function open_php()
    {
        require($this->sourceFile);
        return $lang;           
    }
    private function open_json()
    {
        return json_decode(file_get_contents($this->sourceFile),true);           
    }
    private function open_xml()
    {
        return simplexml_load_file($this->sourceFile);       
    }
    private function open_ini()
    {
        if(function_exists('parse_ini_string'))
            return parse_ini_string(file_get_contents($this->sourceFile),true);
        else
            return parse_ini_file($this->sourceFile,true);            
    } 
    private function save_php($value)
    {
        //not implemented          
    }
    private function save_json($value)
    {
        file_put_contents($this->targetFile,json_encode($value));           
    }
    private function save_xml($value)
    {
        $value->asXML($this->targetFile);    
    }
 }

?>