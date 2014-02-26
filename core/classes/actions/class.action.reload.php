<?php

class ActionReload
{
    public function __construct($args)
    {
        global $neardBs, $neardConfig, $neardBins, $neardTools, $neardApps, $neardHomepage;
        
        // Refresh hostname
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
        
        // Check browser
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Util::getDefaultBrowser());
        }
        
        // Rebuild hosts file
        Util::refactorHostsFile();
        
        // Process neard.ini
        file_put_contents($neardBs->getIniFilePath(), iconv("UTF-8", "WINDOWS-1252", TplApp::process()));
        
        // Process Console config 
        file_put_contents($neardTools->getConsole()->getConf(), TplConsole::process());
        
        // Process Websvn config
        file_put_contents($neardApps->getWebsvn()->getConf(), TplWebsvn::process());
        
        // Process Gitlist config
        file_put_contents($neardApps->getGitlist()->getConf(), TplGitlist::process());
        
        // Refresh PEAR version cache file
        $neardBins->getPhp()->getPearVersion();
        
        // Rebuild alias homepage
        $neardHomepage->refreshAliasContent();
    }

}
