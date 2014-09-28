<?php

class Config
{
    const CFG_LOGS_VERBOSE = 'logsVerbose';
    const CFG_PURGE_LOGS_ON_STARTUP = 'purgeLogsOnStartup';
    const CFG_LANG = 'lang';
    const CFG_TIMEZONE = 'timezone';
    const CFG_NOTEPAD = 'notepad';
    const CFG_SCRIPTS_TIMEOUT = 'scriptsTimeout';
    const CFG_SCRIPTS_DELETE = 'scriptsDelete';
    
    const CFG_DEFAULT_LANG = 'defaultLang';
    const CFG_HOSTNAME = 'hostname';
    const CFG_BROWSER = 'browser';
    const CFG_ONLINE = 'online';
    const CFG_LAUNCH_STARTUP = 'launchStartup';
    
    const ENABLED = 1;
    const DISABLED = 0;
    
    const VERBOSE_SIMPLE = 0;
    const VERBOSE_REPORT = 1;
    const VERBOSE_DEBUG = 2;
    
    private $raw;
    
    public function __construct()
    {
        global $neardBs;
        
        $this->raw = parse_ini_file($neardBs->getConfigFilePath());
        if (!$neardBs->isBootstrap()) {
            $this->raw[self::CFG_LOGS_VERBOSE] = 0;
        }
        
        date_default_timezone_set($this->getTimezone());
    }
    
    public function getRaw($key)
    {
        return $this->raw[$key];
    }
    
    public function replace($key, $value)
    {
        $this->replaceAll(array($key => $value));
    }
    
    public function replaceAll($params)
    {
        global $neardBs;
        
        Util::logDebug('Replace config:');
        $content = file_get_contents($neardBs->getConfigFilePath());
        foreach ($params as $key => $value) {
            Util::logDebug('## ' . $key . ': ' . $value);
            $content = preg_replace('|' . $key . ' = .*|', $key . ' = ' . '"' . $value.'"' , $content);
            $this->raw[$key] = $value;
        }
        
        file_put_contents($neardBs->getConfigFilePath(), $content);
    }

    public function getLang()
    {
        return $this->raw[self::CFG_LANG];
    }

    public function getDefaultLang()
    {
        return $this->raw[self::CFG_DEFAULT_LANG];
    }

    public function getTimezone()
    {
        return $this->raw[self::CFG_TIMEZONE];
    }

    public function isOnline()
    {
        return $this->raw[self::CFG_ONLINE] == self::ENABLED;
    }
    
    public function isLaunchStartup()
    {
        return $this->raw[self::CFG_LAUNCH_STARTUP] == self::ENABLED;
    }

    public function getBrowser()
    {
        return $this->raw[self::CFG_BROWSER];
    }
    
    public function getHostname()
    {
        return $this->raw[self::CFG_HOSTNAME];
    }
    
    public function getScriptsTimeout()
    {
        return intval($this->raw[self::CFG_SCRIPTS_TIMEOUT]);
    }
    
    public function isScriptsDelete()
    {
        return intval($this->raw[self::CFG_SCRIPTS_DELETE]) == Config::ENABLED;
    }

    public function getNotepad()
    {
        return $this->raw[self::CFG_NOTEPAD];
    }

    public function getLogsVerbose()
    {
        return intval($this->raw[self::CFG_LOGS_VERBOSE]);
    }
    
    public function isPurgeLogsOnStartup()
    {
        return intval($this->raw[self::CFG_PURGE_LOGS_ON_STARTUP]) == self::ENABLED;
    }
    
    public function getPaypalLink()
    {
        return 'https://www.paypal.com/cgi-bin/webscr' .
            '?cmd=_donations' .
            '&business=4H86AJZ6M865A' .
            '&item_name=' . urlencode(APP_TITLE) .
            '&no_note=0' .
            '&cn=Message%20%3a' .
            '&no_shipping=1' .
            '&rm=1' .
            '&return=' . urlencode(APP_GITHUB_HOME) .
            '&cancel_return=' . urlencode(APP_GITHUB_HOME) .
            '&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted';
    }
    
}
