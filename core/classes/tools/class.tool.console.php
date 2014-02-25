<?php

class ToolConsole
{
    const CFG_VERSION = 'consoleVersion';
    const CFG_EXE = 'consoleExe';
    const CFG_CONF = 'consoleConf';
    const CFG_SHELL = 'consoleShell';
    const CFG_ROWS = 'consoleRows';
    const CFG_COLS = 'consoleCols';
    
    const SHELL_CMD = 'cmd';
    const SHELL_POWERSHELL = 'powershell';
    
    private $name;
    private $version;
    private $rows;
    private $cols;
    
    private $rootPath;
    private $currentPath;
    private $exe;
    private $conf;
    private $shell;
    private $shellList;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang, $neardTools;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::CONSOLE);
        $this->version = $neardConfig->getRaw(self::CFG_VERSION);
        $this->exe = $neardConfig->getRaw(self::CFG_EXE);
        $this->conf = $neardConfig->getRaw(self::CFG_CONF);
        $this->shell = $neardConfig->getRaw(self::CFG_SHELL);
        $this->rows = intval($neardConfig->getRaw(self::CFG_ROWS));
        $this->cols = intval($neardConfig->getRaw(self::CFG_COLS));
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/console' . $this->version;
        $this->exe = $this->currentPath . '/' . $this->exe;
        $this->conf = $this->currentPath . '/' . $this->conf;
        
        // PowerShell path
        $powerShellPath = $this->getPowerShell();
        
        // Shell list
        $this->shellList[self::SHELL_CMD] = $this->getCmdShell();
        $this->shellList[self::SHELL_POWERSHELL] = $powerShellPath;
        
        // Shell
        if ($this->shell == self::SHELL_POWERSHELL && $powerShellPath !== false) {
            $this->shell = $powerShellPath;
        } else {
            $this->shell = $this->getCmdShell();
        }
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::BIN_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
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
    
    public function getRows()
    {
        return $this->rows;
    }
    
    public function getCols()
    {
        return $this->cols;
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

    public function getConf()
    {
        return $this->conf;
    }
    
    public function getShell()
    {
        return $this->shell;
    }
    
    public function getShellList()
    {
        return $this->shellList;
    }
    
    public function getCmdShell()
    {
        global $neardTools;
        // Customize prompt: http://jpsoft.com/help/prompt.htm
        return '&quot;' . $neardTools->getTccle()->getExe() . '&quot; @&quot;' . $neardTools->getTccle()->getConf() . '&quot; cls &amp; prompt [$e[1;31m$u@' . gethostname() . '$s$e[1;32m$p$e[0m]$s &amp;';
    }
    
    public function getPowerShell()
    {
        return Util::getPowerShellPath();
    }
    
    public function getTabTitleDefault()
    {
        global $neardLang;
        return $neardLang->getValue(Lang::CONSOLE);
    }
    
    public function getTabTitleCmd()
    {
        return $this->getTabTitleDefault() . ' ' . self::SHELL_CMD;
    }
    
    public function getTabTitlePowershell()
    {
        return $this->getTabTitleDefault() . ' ' . self::SHELL_POWERSHELL;
    }
    
    public function getTabTitlePear()
    {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::PEAR) . ' ' . $neardBins->getPhp()->getPearVersion();
    }
    
    public function getTabTitleMysql()
    {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::MYSQL) . ' ' . $neardBins->getMysql()->getVersion();
    }
    
    public function getTabTitleMariadb()
    {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::MARIADB) . ' ' . $neardBins->getMariadb()->getVersion();
    }
    
    public function getTabTitleGit($repoPath = null)
    {
        global $neardLang, $neardTools;
        $result = $neardLang->getValue(Lang::GIT) . ' ' . $neardTools->getGit()->getVersion();
        if ($repoPath != null) {
            $result .= ' - ' . basename($repoPath);
        }
        return $result;
    }
    
    public function getTabTitleSvn($repoPath = null)
    {
        global $neardLang, $neardTools;
        $result = $neardLang->getValue(Lang::SVN) . ' ' . $neardTools->getSvn()->getVersion();
        if ($repoPath != null) {
            $result .= ' - ' . basename($repoPath);
        }
        return $result;
    }
    
    public function getTabTitleNodejs()
    {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::NODEJS) . ' ' . $neardBins->getNodejs()->getVersion();
    }
}
