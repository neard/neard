<?php

class LangProc
{
    private $current;
    private $raw;
    
    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        global $neardCore, $neardConfig;
        $this->raw = null;
        
        $this->current = $neardConfig->getDefaultLang();
        if (!empty($this->current) && in_array($this->current, $this->getList())) {
            $this->current = $neardConfig->getLang();
        }
        
        $this->raw = parse_ini_file($neardCore->getLangsPath() . '/' . $this->current . '.lng');
    }
    
    public function getCurrent()
    {
        return $this->current;
    }
    
    public function getList()
    {
        global $neardCore;
        $result = array();
    
        if ($handle = opendir($neardCore->getLangsPath())) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && Util::endWith($file, '.lng')) {
                    $result[] = str_replace('.lng', '', $file);
                }
            }
            closedir($handle);
        }
    
        return $result;
    }

    public function getValue($key)
    {
        global $neardBs;
       
        if (!isset($this->raw[$key])) {
            $content = '[' . date('Y-m-d H:i:s', time()) . '] ';
            $content .= 'ERROR: Lang var missing ' . $key;
            $content .= ' for ' . $this->current . ' language.' . PHP_EOL;
            file_put_contents($neardBs->getErrorLogFilePath(), $content, FILE_APPEND);
            return $key;
        }
        
        return $this->raw[$key];
    }
}
