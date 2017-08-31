<?php

class TplAppNodejs
{
    const MENU = 'nodejs';
    const MENU_VERSIONS = 'nodejsVersions';
    
    const ACTION_ENABLE = 'enableNodejs';
    const ACTION_SWITCH_VERSION = 'switchNodejsVersion';
    
    public static function process()
    {
        global $neardLang, $neardBins;
    
        return TplApp::getMenuEnable($neardLang->getValue(Lang::NODEJS), self::MENU, get_called_class(), $neardBins->getNodejs()->isEnable());
    }
    
    public static function getMenuNodejs()
    {
        global $neardBins, $neardLang, $neardTools;
        $resultItems = $resultActions = '';
        
        $isEnabled = $neardBins->getNodejs()->isEnable();
        
        // Download
        $resultItems .= TplAestan::getItemLink(
        $neardLang->getValue(Lang::DOWNLOAD_MORE),
            Util::getWebsiteUrl('modules/nodejs', '#releases'),
            false,
            TplAestan::GLYPH_BROWSER
        ) . PHP_EOL;
    
        // Enable
        $tplEnable = TplApp::getActionMulti(
            self::ACTION_ENABLE, array($isEnabled ? Config::DISABLED : Config::ENABLED),
            array($neardLang->getValue(Lang::MENU_ENABLE), $isEnabled ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        $resultItems .= $tplEnable[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplEnable[TplApp::SECTION_CONTENT] . PHP_EOL;
        
        if ($isEnabled) {
            $resultItems .= TplAestan::getItemSeparator() . PHP_EOL;
        
            // Versions
            $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
            $resultItems .= $tplVersions[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplVersions[TplApp::SECTION_CONTENT];
        
            // Console
            $resultItems .= TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitleNodejs()
            ) . PHP_EOL;
    
            // Conf
            $resultItems .= TplAestan::getItemNotepad(basename($neardBins->getNodejs()->getConf()), $neardBins->getNodejs()->getConf()) . PHP_EOL;
        }
        
        return $resultItems . PHP_EOL . $resultActions;
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
    
    public static function getActionEnableNodejs($enable)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::ENABLE, array($neardBins->getNodejs()->getName(), $enable)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionSwitchNodejsVersion($version)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getNodejs()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
}
