<?php

class Apps
{
    private $phpmyadmin;
    private $gitlist;
    private $websvn;
    private $webgrind;
    
    public function __construct()
    {
        
    }

    private function getRootPath($app)
    {
        global $neardBs;
        return $neardBs->getAppsPath() . '/' . $app;
    }

    public function getPhpmyadmin()
    {
        if ($this->phpmyadmin == null) {
            $this->phpmyadmin = new AppPhpmyadmin($this->getRootPath('phpmyadmin'));
        }
        return $this->phpmyadmin;
    }
    
    public function getGitlist()
    {
        if ($this->gitlist == null) {
            $this->gitlist = new AppGitlist($this->getRootPath('gitlist'));
        }
        return $this->gitlist;
    }
    
    public function getWebsvn()
    {
        if ($this->websvn == null) {
            $this->websvn = new AppWebsvn($this->getRootPath('websvn'));
        }
        return $this->websvn;
    }
    
    public function getWebgrind()
    {
        if ($this->webgrind == null) {
            $this->webgrind = new AppWebgrind($this->getRootPath('webgrind'));
        }
        return $this->webgrind;
    }
    
}
