<?php

class AppPhpmyadmin
{
    const ROOT_CFG_VERSION = 'phpmyadminVersion';
    
    const LOCAL_CFG_PHP52 = 'php52';
    const LOCAL_CFG_PHP53 = 'php53';
    const LOCAL_CFG_PHP55 = 'php55';
    
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
            $versions[self::LOCAL_CFG_PHP52] = $this->neardConfRaw[self::LOCAL_CFG_PHP52];
            $versions[self::LOCAL_CFG_PHP53] = $this->neardConfRaw[self::LOCAL_CFG_PHP53];
            $versions[self::LOCAL_CFG_PHP55] = $this->neardConfRaw[self::LOCAL_CFG_PHP55];
        }
        
        foreach ($versions as $key => $versionSub) {
            $neardConfSub = $this->currentPath . '/' . $versionSub . '/neard.conf';
            if (!is_file($neardConfSub)) {
                Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version . ' / ' . $versionSub, $neardConfSub));
            }
            $neardConfRawSub = parse_ini_file($neardConfSub);
            if ($neardConfRawSub !== false) {
                $confSub = $this->currentPath . '/' . $versionSub . '/' . $neardConfRawSub[self::LOCAL_CFG_CONF];
                if (!is_file($confSub)) {
                    Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version . ' / ' . $confSub));
                } else {
                    $this->versions[$key] = array(
                        'version' => $versionSub,
                        'conf' => $confSub
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
    
    public function update($showWindow = false)
    {
        $this->updateConfig(null, $showWindow);
    }
    
    private function updateConfig($version = null, $showWindow = false)
    {
        global $neardBs, $neardBins;
        $version = $version == null ? $this->getVersion() : $version;
        Util::logDebug('Update ' . $this->getName() . ' ' . $version . ' config...');
    
        $alias = $neardBs->getAliasPath() . '/phpmyadmin.conf';
        if (is_file($alias)) {
            $version = $this->getVersionCompatPhp();
            Util::replaceInFile($alias, array(
                '/^Alias\s\/phpmyadmin\s.*/' => 'Alias /phpmyadmin "' . $this->getCurrentPath() . '/' . $version . '/"',
                '/^<Directory\s.*/' => '<Directory "' . $this->getCurrentPath() . '/' . $version . '/">',
            ));
        } else {
            Util::logError($this->getName() . ' alias not found : ' . $alias);
        }
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
    
    public function getVersionCompatPhp($phpVersion = null)
    {
        global $neardBins;
        
        $phpVersion = empty($phpVersion) ? $neardBins->getPhp()->getVersion() : $phpVersion;
        $versions = $this->getVersions();
        $version = $versions[self::LOCAL_CFG_PHP52]['version'];
        if (version_compare($phpVersion, '5.5', '>=')) {
            $version = $versions[self::LOCAL_CFG_PHP55]['version'];
        } elseif (version_compare($phpVersion, '5.3.7', '>=')) {
            $version = $versions[self::LOCAL_CFG_PHP53]['version'];
        }
        
        return $version;
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
