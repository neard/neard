<?php

class ToolConsole extends Module
{
    const ROOT_CFG_VERSION = 'consoleVersion';
    
    const LOCAL_CFG_EXE = 'consoleExe';
    const LOCAL_CFG_CONF = 'consoleConf';
    const LOCAL_CFG_LAUNCH_EXE = 'consoleLaunchExe';
    const LOCAL_CFG_ROWS = 'consoleRows';
    const LOCAL_CFG_COLS = 'consoleCols';
    
    private $exe;
    private $launchExe;
    private $conf;
    private $rows;
    private $cols;

    public function __construct($id, $type) {
        Util::logInitClass($this);
        $this->reload($id, $type);
    }

    public function reload($id = null, $type = null) {
        global $neardConfig, $neardLang;

        $this->name = $neardLang->getValue(Lang::CONSOLE);
        $this->version = $neardConfig->getRaw(self::ROOT_CFG_VERSION);
        parent::reload($id, $type);

        if ($this->neardConfRaw !== false) {
            $this->exe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_EXE];
            $this->launchExe = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_LAUNCH_EXE];
            $this->conf = $this->currentPath . '/' . $this->neardConfRaw[self::LOCAL_CFG_CONF];
            $this->rows = intval($this->neardConfRaw[self::LOCAL_CFG_ROWS]);
            $this->cols = intval($this->neardConfRaw[self::LOCAL_CFG_COLS]);
        }
        
        if (!$this->enable) {
            Util::logInfo($this->name . ' is not enabled!');
            return;
        }
        if (!is_dir($this->currentPath)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_FILE_NOT_FOUND), $this->name . ' ' . $this->version, $this->currentPath));
        }
        if (!is_file($this->neardConf)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_CONF_NOT_FOUND), $this->name . ' ' . $this->version, $this->neardConf));
        }
        if (!is_file($this->exe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->exe));
        }
        if (!is_file($this->launchExe)) {
            Util::logError(sprintf($neardLang->getValue(Lang::ERROR_EXE_NOT_FOUND), $this->name . ' ' . $this->version, $this->launchExe));
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
    
    public function setVersion($version) {
        global $neardConfig;
        $this->version = $version;
        $neardConfig->replace(self::ROOT_CFG_VERSION, $version);
    }
    
    public function getExe() {
        return $this->exe;
    }
    
    public function getLaunchExe() {
        return $this->launchExe;
    }
    
    public function getConf() {
        return $this->conf;
    }
    
    public function getRows() {
        return $this->rows;
    }
    
    public function getCols() {
        return $this->cols;
    }
    
    public function getShell($args = null) {
        if (empty($args)) {
            return 'cmd /k &quot;' . Util::formatWindowsPath($this->launchExe) . '&quot;';
        } else {
            return 'cmd /k &quot;&quot;' . Util::formatWindowsPath($this->getLaunchExe()) . '&quot; &amp; ' . Util::formatWindowsPath($args) . '&quot;';
        }
    }
    
    public function getTabTitleDefault() {
        global $neardLang;
        return $neardLang->getValue(Lang::CONSOLE);
    }
    
    public function getTabTitlePowershell() {
        return 'PowerShell';
    }
    
    public function getTabTitlePear() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::PEAR) . ' ' . $neardBins->getPhp()->getPearVersion(true);
    }
    
    public function getTabTitleMysql() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::MYSQL) . ' ' . $neardBins->getMysql()->getVersion();
    }
    
    public function getTabTitleMariadb() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::MARIADB) . ' ' . $neardBins->getMariadb()->getVersion();
    }
    
    public function getTabTitleMongodb() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::MONGODB) . ' ' . $neardBins->getMongodb()->getVersion();
    }
    
    public function getTabTitlePostgresql() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::POSTGRESQL) . ' ' . $neardBins->getPostgresql()->getVersion();
    }
    
    public function getTabTitleSvn($repoPath = null) {
        global $neardLang, $neardBins;
        $result = $neardLang->getValue(Lang::SVN) . ' ' . $neardBins->getSvn()->getVersion();
        if ($repoPath != null) {
            $result .= ' - ' . basename($repoPath);
        }
        return $result;
    }
    
    public function getTabTitleGit($repoPath = null) {
        global $neardLang, $neardTools;
        $result = $neardLang->getValue(Lang::GIT) . ' ' . $neardTools->getGit()->getVersion();
        if ($repoPath != null) {
            $result .= ' - ' . basename($repoPath);
        }
        return $result;
    }
    
    public function getTabTitleNodejs() {
        global $neardLang, $neardBins;
        return $neardLang->getValue(Lang::NODEJS) . ' ' . $neardBins->getNodejs()->getVersion();
    }
    
    public function getTabTitleComposer() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::COMPOSER) . ' ' . $neardTools->getComposer()->getVersion();
    }
    
    public function getTabTitlePhpMetrics() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::PHPMETRICS) . ' ' . $neardTools->getPhpMetrics()->getVersion();
    }
    
    public function getTabTitlePhpUnit() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::PHPUNIT) . ' ' . $neardTools->getPhpUnit()->getVersion();
    }
    
    public function getTabTitleDrush() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::DRUSH) . ' ' . $neardTools->getDrush()->getVersion();
    }
    
    public function getTabTitleWpCli() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::WPCLI) . ' ' . $neardTools->getWpCli()->getVersion();
    }
    
    public function getTabTitlePython() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::PYTHON) . ' ' . $neardTools->getPython()->getVersion();
    }
    
    public function getTabTitleRuby() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::RUBY) . ' ' . $neardTools->getRuby()->getVersion();
    }
    
    public function getTabTitleYarn() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::YARN) . ' ' . $neardTools->getYarn()->getVersion();
    }
    
    public function getTabTitlePerl() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::PERL) . ' ' . $neardTools->getPerl()->getVersion();
    }
    
    public function getTabTitleGhostscript() {
        global $neardLang, $neardTools;
        return $neardLang->getValue(Lang::GHOSTSCRIPT) . ' ' . $neardTools->getGhostscript()->getVersion();
    }
}
