<?php

class Apps
{
    private $phpmyadmin;
    private $gitlist;
    private $websvn;
    private $webgrind;
    private $adminer;
    private $phpmemadmin;
    private $phppgadmin;
    
    public function __construct()
    {
    }

    private function getRootPath($app)
    {
        global $neardBs;
        return $neardBs->getAppsPath() . '/' . $app;
    }
    
    public function update()
    {
        Util::logInfo('Update apps config');
        foreach ($this->getAll() as $tool) {
            $tool->update();
        }
    }
    
    public function getAll()
    {
        return array(
            $this->getAdminer(),
            $this->getGitlist(),
            $this->getPhpmemadmin(),
            $this->getPhpmyadmin(),
            $this->getPhppgadmin(),
            $this->getWebgrind(),
            $this->getWebsvn(),
        );
    }
    
    public function getAdminer()
    {
        if ($this->adminer == null) {
            $this->adminer = new AppAdminer($this->getRootPath('adminer'));
        }
        return $this->adminer;
    }
    
    public function getGitlist()
    {
        if ($this->gitlist == null) {
            $this->gitlist = new AppGitlist($this->getRootPath('gitlist'));
        }
        return $this->gitlist;
    }
    
    public function getPhpmemadmin()
    {
        if ($this->phpmemadmin == null) {
            $this->phpmemadmin = new AppPhpmemadmin($this->getRootPath('phpmemadmin'));
        }
        return $this->phpmemadmin;
    }

    public function getPhpmyadmin()
    {
        if ($this->phpmyadmin == null) {
            $this->phpmyadmin = new AppPhpmyadmin($this->getRootPath('phpmyadmin'));
        }
        return $this->phpmyadmin;
    }
    
    public function getPhppgadmin()
    {
        if ($this->phppgadmin == null) {
            $this->phppgadmin = new AppPhppgadmin($this->getRootPath('phppgadmin'));
        }
        return $this->phppgadmin;
    }
    
    public function getWebgrind()
    {
        if ($this->webgrind == null) {
            $this->webgrind = new AppWebgrind($this->getRootPath('webgrind'));
        }
        return $this->webgrind;
    }
    
    public function getWebsvn()
    {
        if ($this->websvn == null) {
            $this->websvn = new AppWebsvn($this->getRootPath('websvn'));
        }
        return $this->websvn;
    }
}
