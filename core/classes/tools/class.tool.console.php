<?php

class ToolConsole
{
    const ROOT_CFG_VERSION = 'consoleVersion';
    
    const LOCAL_CFG_EXE = 'consoleExe';
    const LOCAL_CFG_CONF = 'consoleConf';
    const LOCAL_CFG_TCCLE_EXE = 'consoleTccleExe';
    const LOCAL_CFG_SHELL = 'consoleShell';
    const LOCAL_CFG_ROWS = 'consoleRows';
    const LOCAL_CFG_COLS = 'consoleCols';
    
    const SHELL_CMD = 'cmd';
    const SHELL_POWERSHELL = 'powershell';
    
    private $name;
    private $version;
    
    private $rootPath;
    private $currentPath;
    private $neardConf;
    private $neardConfRaw;
    
    private $exe;
    private $tccleExe;
    private $conf;
    private $shell;
    private $shellList;
    private $rows;
    private $cols;
    
    public function __construct($rootPath)
    {
        global $neardBs, $neardConfig, $neardLang, $neardTools;
        Util::logInitClass($this);
        
        $this->name = $neardLang->getValue(Lang::CONSOLE);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        
        $this->rootPath = $rootPath;
        $this->currentPath = $rootPath . '/console' . $this->version;
        $this->neardConf = $this->currentPath . '/neard.conf';
        
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        
        $this->neardConfRaw = parse_ini_file($this->neardConf);
        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->tccleExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_TCCLE_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->shell = $this->neardConfRaw[self::LOCAL_CFG_SHELL];
            $this->rows = intval($this->neardConfRaw[self::LOCAL_CFG_ROWS]);
            $this->cols = intval($this->neardConfRaw[self::LOCAL_CFG_COLS]);
            
            // PowerShell path
            $powerShellPath = $this->getPowerShell();
            
            // Shell list
            $this->shellList[self::SHELL_CMD] = $this->tccleExe;
            $this->shellList[self::SHELL_POWERSHELL] = $powerShellPath;
            
            // Shell
            if ($this->shell == self::SHELL_POWERSHELL && $powerShellPath !== false) {
                $this->shell = $powerShellPath;
            } else {
                $this->shell = $this->tccleExe;
            }
        }
        
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->tccleExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->tccleExe));
        }
        if (!is_file($this->conf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->conf));
        }
        if (!is_numeric($this->rows) || $this->rows <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_ROWS, $this->rows));
        }
        if (!is_numeric($this->cols) || $this->cols <= 0) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_INVALID_PARAMETER), self::LOCAL_CFG_COLS, $this->cols));
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
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardTools, $neardWinbinder;
        $version = $version == null ? $this->getVersion() : $version;
        Util::logDebug('Update ' . $this->getName() . ' ' . $version . ' config...');
        
        //TODO: Update config
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
    
    public function getExe()
    {
        return $this->exe;
    }
    
    public function getTccleExe()
    {
        return $this->tccleExe;
    }
    
    public function getConf()
    {
        return $this->conf;
    }
    
    public function getRows()
    {
        return $this->rows;
    }
    
    public function getCols()
    {
        return $this->cols;
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
        return '&quot;' . $this->getTccleExe() . '&quot;';
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
        return $neardLang->getValue(Lang::PEAR) . ' ' . $neardBins->getPhp()->getPearVersion(true);
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
    
    public function getTabTitleComposer()
    {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::COMPOSER) . ' ' . $neardTools->getComposer()->getVersion();
    }
    
    public function getTabTitlePhpUnit()
    {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::PHPUNIT) . ' ' . $neardTools->getPhpUnit()->getVersion();
    }
}
