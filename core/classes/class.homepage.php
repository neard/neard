<?php

class Homepage
{
    public function __construct()
    {
        Util::logInitClass($this);
    }
    
    public function getPath()
    {
        global $neardCore;
        return $neardCore->getResourcesPath(false) . '/homepage';
    }
    
    public function getUrl()
    {
        global $neardBins;
        return 'http://localhost' . ($neardBins->getApache()->getPort() != 80 ? ':' . $neardBins->getApache()->getPort() : '');
    }
    
    public function getResourcesUrl()
    {
        global $neardConfig;
        return $this->getUrl() . '/' . md5(APP_TITLE . $neardConfig->getAppVersion());
    }
    
    public function getAliasFilePath()
    {
        return $this->getPath() . '/alias.conf';
    }
    
    public function refreshAliasContent()
    {
        global $neardConfig;
    
        $result = 'Alias /' . md5(APP_TITLE . $neardConfig->getAppVersion()) .
            ' "' . $this->getPath() . '/"' . PHP_EOL .
            '<Directory "' . $this->getPath() . '/">' . PHP_EOL .
            '    Options FollowSymLinks MultiViews' . PHP_EOL;
    
        if (Util::isOnline()) {
            $result .= '    Order Allow,Deny' . PHP_EOL .
            '    Allow from all';
        } else {
            $result .= '    Order Deny,Allow' . PHP_EOL .
            '    Deny from all' . PHP_EOL .
            '    Allow from 127.0.0.1 ::1 localhost';
        }
    
        $result .= PHP_EOL . '</Directory>';
        return file_put_contents($this->getAliasFilePath(), $result) !== false;
    }
}
