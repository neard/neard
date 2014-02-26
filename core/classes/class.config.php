<?php

class Config
{
    const CFG_LANG = 'lang';
    const CFG_DEFAULT_LANG = 'defaultLang';
    const CFG_TIMEZONE = 'timezone';
    const CFG_STATUS = 'status';
    const CFG_HOSTNAME = 'hostname';
    const CFG_BROWSER = 'browser';
    const CFG_NOTEPAD = 'notepad';
    
    const CFG_APP_VERSION = 'appVersion';
    const CFG_APP_LOGS_VERBOSE = 'appLogsVerbose';
    const CFG_APP_PURGE_LOGS_ON_STARTUP = 'appPurgeLogsOnStartup';
    
    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    
    private $raw;
    
    public function __construct()
    {
        global $neardBs;
        
        $this->raw = parse_ini_file($neardBs->getConfigFilePath());
        if (!$neardBs->isBootstrap()) {
            $this->raw[self::CFG_APP_LOGS_VERBOSE] = 0;
        }
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

    public function getStatus()
    {
        return $this->raw[self::CFG_STATUS];
    }

    public function getBrowser()
    {
        return $this->raw[self::CFG_BROWSER];
    }
    
    public function getHostname()
    {
        return $this->raw[self::CFG_HOSTNAME];
    }

    public function getNotepad()
    {
        return $this->raw[self::CFG_NOTEPAD];
    }

    public function getAppVersion()
    {
        return $this->raw[self::CFG_APP_VERSION];
    }

    public function getAppLogsVerbose()
    {
        return intval($this->raw[self::CFG_APP_LOGS_VERBOSE]);
    }
    
    public function getAppPurgeLogsOnStartup()
    {
        return intval($this->raw[self::CFG_APP_PURGE_LOGS_ON_STARTUP]) == 1;
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
    
    public function getBitcoinAddress()
    {
        return '1BdhK62JY2xQKXjmvLLA8Dpbit1uJ5JkrC';
    }
    
}
