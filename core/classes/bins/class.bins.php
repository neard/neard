<?php

class Bins
{
    private $apache;
    private $php;
    private $mysql;
    private $mariadb;
    private $nodejs;
    
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

    public function getServices()
    {
        return array(
            BinApache::SERVICE_NAME => $this->getApache()->getService(),
            BinMysql::SERVICE_NAME => $this->getMysql()->getService(),
            BinMariadb::SERVICE_NAME => $this->getMariadb()->getService(),
        );
    }
}
