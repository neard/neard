<?php

class ActionReload
{
    public function __construct($args)
    {
        global $neardBs, $neardConfig, $neardBins, $neardTools, $neardApps, $neardHomepage;
        
        // Start loading
        Util::startLoading();
        
        // Refresh hostname
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
        
        // Check browser
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Vbs::getDefaultBrowser());
        }
        
        // Rebuild hosts file
        Util::refactorWindowsHosts();
        
        // Process neard.ini
        file_put_contents($neardBs->getIniFilePath(), Util::utf8ToCp1252(TplApp::process()));
        
        // Process Console config 
        TplConsole::process();
        
        // Process Sublimetext config
        TplSublimetext::process();
        
        // Process Websvn config
        TplWebsvn::process();
        
        // Process Gitlist config
        TplGitlist::process();
        
        // Refresh PEAR version cache file
        $neardBins->getPhp()->getPearVersion();
        
        // Rebuild alias homepage
        $neardHomepage->refreshAliasContent();
    }

}
