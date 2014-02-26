<?php

class TplAppNodejs
{
    const MENU = 'nodejs';
    const MENU_VERSIONS = 'nodejsVersions';
    
    const ACTION_SWITCH_VERSION = 'switchNodejsVersion';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::NODEJS), self::MENU, get_called_class());
    }
    
    public static function getMenuNodejs()
    {
        global $neardBins, $neardLang, $neardTools;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitleNodejs()
            ) . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuNodejsVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getNodejs()->getVersionList() as $version) {
            $tplSwitchNodejsVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getNodejs()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchNodejsVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchNodejsVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchNodejsVersion($version)
    {
        global $neardBs, $neardCore, $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getNodejs()->getName(), $version)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
}
