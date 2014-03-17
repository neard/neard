<?php

class ToolGit
{
    const CFG_VERSION = 'gitVersion';
    const CFG_EXE = 'gitExe';
    const CFG_BASH = 'gitBash';
    const CFG_REPOS = 'gitRepos';
    
    private $name;
    private $version;
    private $repos;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    private $bash;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::GIT);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->bash = $neardConfig->getRaw(self::CFG_BASH);
        $this->repos = $neardConfig->getRaw(self::CFG_REPOS);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/git' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->bash = $this->currentPath . '/' . $this->bash;
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->bash)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->bash));
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
                    $foundRepos = Vbs::findReposVbs($repo, '.git', 'config');
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

    public function getBash()
    {
        return $this->bash;
    }
    
}
