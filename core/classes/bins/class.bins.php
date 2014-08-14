<?php

class Bins
{
    private $apache;
    private $php;
    private $mysql;
    private $mariadb;
    private $nodejs;
    private $filezilla;
    
    public function __construct()
    {
        Util::logInitClass($this);
    }
    
    private function getRootPath($bin)
    {
        global $neardBs;
        return $neardBs->getBinPath() . '/' . $bin;
    }
    
    public function reload()
    {
        Util::logInfo('Reload bins');
        $this->getApache()->reload();
        $this->getPhp()->reload();
        $this->getMysql()->reload();
        $this->getMariadb()->reload();
        $this->getNodejs()->reload();
        $this->getFilezilla()->reload();
    }

    public function getApache()
    {
        if ($this->apache == null) {
            $this->apache = new BinApache($this->getRootPath('apache'));
        }
        return $this->apache;
    }

    public function getPhp()
    {
        if ($this->php == null) {
            $this->php = new BinPhp($this->getRootPath('php'));
        }
        return $this->php;
    }

    public function getMysql()
    {
        if ($this->mysql == null) {
            $this->mysql = new BinMysql($this->getRootPath('mysql'));
        }
        return $this->mysql;
    }

    public function getMariadb()
    {
        if ($this->mariadb == null) {
            $this->mariadb = new BinMariadb($this->getRootPath('mariadb'));
        }
        return $this->mariadb;
    }

    public function getNodejs()
    {
        if ($this->nodejs == null) {
            $this->nodejs = new BinNodejs($this->getRootPath('nodejs'));
        }
        return $this->nodejs;
    }
    
    public function getFilezilla()
    {
        if ($this->filezilla == null) {
            $this->filezilla = new BinFilezilla($this->getRootPath('filezilla'));
        }
        return $this->filezilla;
    }

    public function getServices()
    {
        return array(
            BinApache::SERVICE_NAME => $this->getApache()->getService(),
            BinMysql::SERVICE_NAME => $this->getMysql()->getService(),
            BinMariadb::SERVICE_NAME => $this->getMariadb()->getService(),
            BinFilezilla::SERVICE_NAME => $this->getFilezilla()->getService(),
        );
    }
    
    public function getServicesStartup()
    {
        $result = array();
        
        if ($this->getApache()->getLaunchStartup() == BinApache::LAUNCH_STARTUP_ON) {
            $result[BinApache::SERVICE_NAME] = $this->getApache()->getService();
        }
        if ($this->getMysql()->getLaunchStartup() == BinMysql::LAUNCH_STARTUP_ON) {
            $result[BinMysql::SERVICE_NAME] = $this->getMysql()->getService();
        }
        if ($this->getMariadb()->getLaunchStartup() == BinMariadb::LAUNCH_STARTUP_ON) {
            $result[BinMariadb::SERVICE_NAME] = $this->getMariadb()->getService();
        }
        if ($this->getFilezilla()->getLaunchStartup() == BinFilezilla::LAUNCH_STARTUP_ON) {
            $result[BinFilezilla::SERVICE_NAME] = $this->getFilezilla()->getService();
        }
        
        return $result;
    }
}
