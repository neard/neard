<?php

class ActionReload
{
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardConfig, $neardBins, $neardTools, $neardApps, $neardHomepage;
        
        if (file_exists($neardCore->getExec())) {
            return;
        }
        
        // Start loading
        Util::startLoading();
        
        // Refresh hostname
        $neardConfig->replace(Config::CFG_HOSTNAME, gethostname());
        
        // Refresh launch startup
        $neardConfig->replace(Config::CFG_LAUNCH_STARTUP, Util::isLaunchStartup() ? Config::ENABLED : Config::DISABLED);
        
        // Check browser
        $currentBrowser = $neardConfig->getBrowser();
        if (empty($currentBrowser) || !file_exists($currentBrowser)) {
            $neardConfig->replace(Config::CFG_BROWSER, Vbs::getDefaultBrowser());
        }
        
        // Process neard.ini
        file_put_contents($neardBs->getIniFilePath(), Util::utf8ToCp1252(TplApp::process()));
        
        // Process Console config 
        TplConsole::process();
        
        // Process Notepad config
        TplNotepad2Mod::process();
        
        // Process Websvn config
        TplWebsvn::process();
        
        // Process Gitlist config
        TplGitlist::process();
        
        // Refresh PEAR version cache file
        $neardBins->getPhp()->getPearVersion();
        
        // Rebuild alias homepage
        $neardHomepage->refreshAliasContent();
        
        // Rebuild _commons.js
        $neardHomepage->refreshCommonsJsContent();
    }

}
