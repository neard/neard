<?php

class AppPhpmyadmin
{
    const ROOT_CFG_VERSION = 'phpmyadminVersion';
    
    const LOCAL_CFG_40 = 'phpmyadmin40';
    const LOCAL_CFG_44 = 'phpmyadmin44';
    const LOCAL_CFG_45 = 'phpmyadmin45';
    
    const LOCAL_CFG_CONF = 'phpmyadminConf';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $versions;
    private $confs;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::PHPMYADMIN);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/phpmyadmin' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        
        $versions = array();
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $versions['40'] = $this->neardConfRaw[self::LOCAL_CFG_40];
            $versions['44'] = $this->neardConfRaw[self::LOCAL_CFG_44];
            $versions['45'] = $this->neardConfRaw[self::LOCAL_CFG_45];
        }
        
        foreach ($versions as $key => $version4) {
            $neardConf4 = $this->currentPath . '/' . $version4 . '/neard.conf';
            if (!is_file($neardConf4)) {
                Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version . ' / ' . $version4, $neardConf4));
            }
            $neardConfRaw4 = parse_ini_file($neardConf4);
            if ($neardConfRaw4 !== false) {
                $conf4 = $this->currentPath . '/' . $version4 . '/' . $neardConfRaw4[self::LOCAL_CFG_CONF];
                if (!is_file($conf4)) {
                    Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version . ' / ' . $conf4));
                } else {
                    $this->versions[$key] = array(
                        'version' => $version4,
                        'conf' => $conf4
                    );
                }
            }
        }
    }
    
    public function __toString()
    {
        return $this->getName();
    }
    
    private function replace($key, $value)
    {
        $this->replaceAll(array($key => $value));
    }
    
    private function replaceAll($params)
    {
        $content = file_get_contents($this->neardConf);
    
        foreach ($params as $key => $value) {
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"' , $content);
            $this->neardConfRaw[$key] = $value;
        }
    
        file_put_contents($this->neardConf, $content);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getVersionList()
    {
        return Util::getVersionList($this->getRootPath());
    }

    public function getVersion()
    {
        return $this->version;
    }
    
    public function getVersions()
    {
        return $this->versions;
    }
    
    public function getVersionsStr()
    {
        $result = '';
        foreach ($this->versions as $version => $data) {
            if (!empty($result)) {
                $result .= ' / ';
            }
            $result .= $data['version'];
        }
        return $result;
    }
    
    public function setVersion($version)
    {
        global $neardConfig;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }

    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
    }
    
    public function getConfs()
    {
        $result = array();
        foreach ($this->versions as $version => $data) {
            $result[] = $data['conf'];
        }
        return $result;
    }

    public function getConf()
    {
        return $this->conf;
    }
}
