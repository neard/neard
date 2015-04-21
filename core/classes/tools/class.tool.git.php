<?php

class ToolGit
{
    const ROOT_CFG_VERSION = 'gitVersion';
    
    const LOCAL_CFG_EXE = 'gitExe';
    const LOCAL_CFG_BASH = 'gitBash';
    const LOCAL_CFG_SCAN_STARTUP = 'gitScanStartup';
    
    const REPOS_FILE = 'repos.dat';
    const REPOS_CACHE_FILE = 'reposCache.dat';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $reposFile;
    private $reposCacheFile;
    private $repos;
    
    private $exe;
    private $bash;
    private $scanStartup;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::GIT);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/git' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        $this->reposFile = $this->currentPath . '/' . self::REPOS_FILE;
        $this->reposCacheFile = $this->currentPath . '/' . self::REPOS_CACHE_FILE;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        if (!is_file($this->reposFile)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->reposFile));
        }
        
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->bash = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_BASH];
            $this->scanStartup = $this->neardConfRaw[self::LOCAL_CFG_SCAN_STARTUP];
        }
        
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->bash)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->bash));
        }
        
        if (is_file($this->reposFile)) {
            $this->repos = explode(PHP_EOL, file_get_contents($this->reposFile));
            $rebuildRepos = array();
            foreach ($this->repos as $repo) {
                $repo = trim($repo);
                if (stripos($repo, ':') === false) {
                    $repo = $neardBs->getRootPath() . '/' . $repo;
                }
                if (is_dir($repo)) {
                    $rebuildRepos[] = Util::formatUnixPath($repo);
                } else {
                    Util::logWarning($this->name . ' repository not found: ' . $repo);
                }
            }
            $this->repos = $rebuildRepos;
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
    
    public function findRepos($cache = true)
    {
        $result = array();
        
        if ($cache) {
            if (file_exists($this->reposCacheFile)) {
                $repos = file($this->reposCacheFile);
                foreach ($repos as $repo) {
                    array_push($result, trim($repo));
                }
            }
        } else {
            if (!empty($this->repos)) {
                foreach ($this->repos as $repo) {
                    $foundRepos = Vbs::findReposVbs($repo, '.git', 'config');
                    if (!empty($foundRepos)) {
                        foreach ($foundRepos as $foundRepo) {
                            array_push($result, $foundRepo);
                        }
                    }
                }
            }
            $strResult = implode(PHP_EOL, $result);
            file_put_contents($this->reposCacheFile, $strResult);
        }
        
        return $result;
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
    
    public function getRepos()
    {
        return $this->repos;
    }

    public function getExe()
    {
        return $this->exe;
    }

    public function getBash()
    {
        return $this->bash;
    }
    
    public function isScanStartup()
    {
        return $this->scanStartup == Config::ENABLED;
    }
    
    public function setScanStartup($scanStartup)
    {
        $this->scanStartup = $scanStartup;
        Util::replaceInFile($this->neardConf, array(
            '/^' . self::LOCAL_CFG_SCAN_STARTUP . '/' => self::LOCAL_CFG_SCAN_STARTUP . ' = "' . $this->scanStartup . '"'
        ));
    }
}
