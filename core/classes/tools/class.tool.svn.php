<?php

class ToolSvn
{
    const CFG_VERSION = 'svnVersion';
    const CFG_EXE = 'svnExe';
    const CFG_ADMIN = 'svnAdmin';
    const CFG_SERVER = 'svnServer';
    const CFG_REPOS = 'svnRepos';
    
    private $name;
    private $version;
    private $server;
    private $repos;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    private $admin;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::SVN);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->admin = $neardConfig->getRaw(self::CFG_ADMIN);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->server = $neardConfig->getRaw(self::CFG_SERVER);
        $this->repos = $neardConfig->getRaw(self::CFG_REPOS);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/svn' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->admin = $this->currentPath . '/' . $this->admin;
        $this->server = $neardBs->getRootPath() . '/' . $this->server;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->admin)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->admin));
        }
        
        if (!empty($this->repos)) {
            $rebuildRepos = array();
            foreach ($this->getRepos() as $repo) {
                if (stripos($repo, ':') === false) {
                    $repo = $this->currentPath . '/' . $repo;
                }
                if (is_dir($repo)) {
                    $rebuildRepos[] = Util::formatUnixPath($repo);
                }
            }
            $this->repos = $rebuildRepos;
        }
    }
    
    public function findRepos($cache = true)
    {
        $result = array();
        $reposCacheFile = $this->currentPath . '/reposCache.dat';
    
        if ($cache) {
            if (file_exists($reposCacheFile)) {
                $repos = file($reposCacheFile);
                foreach ($repos as $repo) {
                    array_push($result, trim($repo));
                }
            }
        } else {
            if (!empty($this->repos)) {
                foreach ($this->getRepos() as $repo) {
                    $foundRepos = Vbs::findReposVbs($repo, '.svn', 'entries');
                    if (!empty($foundRepos)) {
                        foreach ($foundRepos as $foundRepo) {
                            array_push($result, $foundRepo);
                        }
                    }
                }
            }
            $strResult = implode(PHP_EOL, $result);
            file_put_contents($reposCacheFile, $strResult);
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
    
    public function getServer()
    {
        return $this->server;
    }

    public function getRepos()
    {
        return $this->repos;
    }

    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function getCurrentPath()
    {
        return $this->currentPath;
    }
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getAdmin()
    {
        return $this->admin;
    }

}
