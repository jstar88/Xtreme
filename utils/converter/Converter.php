<?php
class Converter
{
    private $root;
    private $sourceFile;
    private $targetFile;
    private $sourceExt;
    private $targetExt;
    private $php_vars;
    
    public function __construct($sourceFile='',$targetFile='',$sourceExt='mo',$targetExt='json',$php_vars=array('lang','langSystem'))
    {
        $this->root=dirname(__FILE__);
        $this->sourceFile=$sourceFile;
        $this->targetFile=$targetFile; 
        $this->sourceExt=$sourceExt; 
        $this->targetExt=$targetExt; 
        $this->php_vars=$php_vars; 
    }
    public function set_phpVars($new){
        $this->php_vars=$new; 
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
        if($this->sourceExt=='mo')
            $funcOpen='open_php'; 
        else  
            $funcOpen='open_'.$this->sourceExt;
        $langArray=$this->{$funcOpen}();
        $funcSave='save_'.$this->targetExt;  
        $this->{$funcSave}($langArray);          
    }
    
    private function open_php()
    {   
        require($this->getSourcePath());
        $container=array();
        foreach($this->php_vars as $var)
            if(isset($$var))
               $container=array_merge_recursive($container,$$var);
        return $container; 
                 
    }
    private function open_json()
    {
        return json_decode(file_get_utf8_contents($this->getSourcePath()),true);           
    }
    private function open_xml()
    {
        return simplexml_load_file($this->getSourcePath());       
    }
    private function open_ini()
    {
        if(function_exists('parse_ini_string'))
            return parse_ini_string(file_get_utf8_contents($this->getSourcePath()),true);
        else
            return parse_ini_file($this->getSourcePath(),true);            
    } 
    private function save_php($value,$name='')
    {
        $name= empty($name) ? $this->php_vars[0] : $name;
        file_put_contents($this->getTargetPath(),"<?php $$name=".  var_export($value,true) ."; ?>");         
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
    private function check_utf8($str) {
    $len = strlen($str);
    for($i = 0; $i < $len; $i++){
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
   }
   private function file_get_utf8_contents($path){
      $contents=file_get_contents($path);
      if(!$this->check_utf8($contents))
         return utf8_encode($contents);  
      return $contents;
   }
 }

?>