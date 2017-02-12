<?php

class Bins
{
    private $mailhog;
    private $memcached;
    private $apache;
    private $php;
    private $mysql;
    private $mariadb;
    private $mongodb;
    private $postgresql;
    private $nodejs;
    private $filezilla;
    private $svn;
    
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
        foreach ($this->getAll() as $bin) {
            $bin->reload();
        }
    }
    
    public function update()
    {
        Util::logInfo('Update bins config');
        foreach ($this->getAll() as $bin) {
            $bin->update();
        }
    }
    
    public function getAll()
    {
        return array(
            $this->getMailhog(),
            $this->getMemcached(),
            $this->getApache(),
            $this->getFilezilla(),
            $this->getMariadb(),
            $this->getMongodb(),
            $this->getPostgresql(),
            $this->getMysql(),
            $this->getSvn(),
            $this->getPhp(),
            $this->getNodejs(),
        );
    }
    
    public function getMailhog()
    {
        if ($this->mailhog == null) {
            $this->mailhog = new BinMailhog($this->getRootPath('mailhog'));
        }
        return $this->mailhog;
    }
    
    public function getMemcached()
    {
        if ($this->memcached == null) {
            $this->memcached = new BinMemcached($this->getRootPath('memcached'));
        }
        return $this->memcached;
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
    
    public function getMongodb()
    {
        if ($this->mongodb == null) {
            $this->mongodb = new BinMongodb($this->getRootPath('mongodb'));
        }
        return $this->mongodb;
    }
    
    public function getPostgresql()
    {
        if ($this->postgresql == null) {
            $this->postgresql = new BinPostgresql($this->getRootPath('postgresql'));
        }
        return $this->postgresql;
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
    
    public function getSvn()
    {
        if ($this->svn == null) {
            $this->svn = new BinSvn($this->getRootPath('svn'));
        }
        return $this->svn;
    }
    
    public function getLogsPath()
    {
        return array(
            $this->getFilezilla()->getLogsPath(),
        );
    }

    public function getServices()
    {
        $result = array();
        
        if ($this->getMailhog()->isEnable()) {
            $result[BinMailhog::SERVICE_NAME] = $this->getMailhog()->getService();
        }
        if ($this->getMemcached()->isEnable()) {
            $result[BinMemcached::SERVICE_NAME] = $this->getMemcached()->getService();
        }
        if ($this->getApache()->isEnable()) {
            $result[BinApache::SERVICE_NAME] = $this->getApache()->getService();
        }
        if ($this->getMysql()->isEnable()) {
            $result[BinMysql::SERVICE_NAME] = $this->getMysql()->getService();
        }
        if ($this->getMariadb()->isEnable()) {
            $result[BinMariadb::SERVICE_NAME] = $this->getMariadb()->getService();
        }
        if ($this->getMongodb()->isEnable()) {
            $result[BinMongodb::SERVICE_NAME] = $this->getMongodb()->getService();
        }
        if ($this->getPostgresql()->isEnable()) {
            $result[BinPostgresql::SERVICE_NAME] = $this->getPostgresql()->getService();
        }
        if ($this->getFilezilla()->isEnable()) {
            $result[BinFilezilla::SERVICE_NAME] = $this->getFilezilla()->getService();
        }
        if ($this->getSvn()->isEnable()) {
            $result[BinSvn::SERVICE_NAME] = $this->getSvn()->getService();
        }
        
        return $result;
    }
}
