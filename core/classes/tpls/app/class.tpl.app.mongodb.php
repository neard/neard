<?php

class TplAppMongodb
{
    const MENU = 'mongodb';
    const MENU_VERSIONS = 'mongodbVersions';
    const MENU_SERVICE = 'mongodbService';
    const MENU_DEBUG = 'mongodbDebug';
    
    const ACTION_ENABLE = 'enableMongodb';
    const ACTION_SWITCH_VERSION = 'switchMongodbVersion';
    const ACTION_CHANGE_PORT = 'changeMongodbPort';
    const ACTION_INSTALL_SERVICE = 'installMongodbService';
    const ACTION_REMOVE_SERVICE = 'removeMongodbService';
    
    public static function process()
    {
        global $neardLang, $neardBins;
    
        return TplApp::getMenuEnable($neardLang->getValue(Lang::MONGODB), self::MENU, get_called_class(), $neardBins->getMongodb()->isEnable());
    }
    
    public static function getMenuMongodb()
    {
        global $neardBs, $neardConfig, $neardBins, $neardLang, $neardTools;
        $resultItems = $resultActions = '';
        
        $isEnabled = $neardBins->getMongodb()->isEnable();
        
        // Download
        $resultItems .= TplAestan::getItemLink(
            $neardLang->getValue(Lang::DOWNLOAD_MORE),
            Util::getWebsiteUrl('modules/mongodb', '#releases'),
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
            $resultActions .= $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL;
        
            // Service
            $tplService = TplApp::getMenu($neardLang->getValue(Lang::SERVICE), self::MENU_SERVICE, get_called_class());
            $resultItems .= $tplService[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplService[TplApp::SECTION_CONTENT] . PHP_EOL;
        
            // Debug
            $tplDebug = TplApp::getMenu($neardLang->getValue(Lang::DEBUG), self::MENU_DEBUG, get_called_class());
            $resultItems .= $tplDebug[TplApp::SECTION_CALL] . PHP_EOL;
            $resultActions .= $tplDebug[TplApp::SECTION_CONTENT];
            
            // Console
            $resultItems .= TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitleMongodb()
            ) . PHP_EOL;
            
            // Status page
            $resultItems .= TplAestan::getItemExe(
                $neardLang->getValue(Lang::STATUS_PAGE),
                $neardConfig->getBrowser(),
                TplAestan::GLYPH_WEB_PAGE,
                $neardBs->getLocalUrl() . ':' . $neardBins->getMongodb()->getWebPort()
            ) . PHP_EOL;
    
            // Conf
            $resultItems .= TplAestan::getItemNotepad(basename($neardBins->getMongodb()->getConf()), $neardBins->getMongodb()->getConf()) . PHP_EOL;
    
            // Logs
            $resultItems .= TplAestan::getItemNotepad(basename($neardBins->getMongodb()->getErrorLog()), $neardBins->getMongodb()->getErrorLog()) . PHP_EOL;
        }
        
        return $resultItems . PHP_EOL . $resultActions;
    }
    
    public static function getMenuMongodbVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getMongodb()->getVersionList() as $version) {
            $tplSwitchMongodbVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getMongodb()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchMongodbVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchMongodbVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionEnableMongodb($enable)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::ENABLE, array($neardBins->getMongodb()->getName(), $enable)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionSwitchMongodbVersion($version)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getMongodb()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuMongodbService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isInstalled = $neardBins->getMongodb()->getService()->isInstalled();
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getMongodb()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getMongodb()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getMongodb()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getMongodb()->getName(), $neardBins->getMongodb()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getMongodb()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL;
        
        if (!$isInstalled) {
            $tplInstallService = TplApp::getActionMulti(
                self::ACTION_INSTALL_SERVICE, null,
                array($neardLang->getValue(Lang::MENU_INSTALL_SERVICE), TplAestan::GLYPH_SERVICE_INSTALL),
                $isInstalled, get_called_class()
            );
        
            $result .= $tplInstallService[TplApp::SECTION_CALL] . PHP_EOL . PHP_EOL .
            $tplInstallService[TplApp::SECTION_CONTENT] . PHP_EOL;
        } else {
            $tplRemoveService = TplApp::getActionMulti(
                self::ACTION_REMOVE_SERVICE, null,
                array($neardLang->getValue(Lang::MENU_REMOVE_SERVICE), TplAestan::GLYPH_SERVICE_REMOVE),
                !$isInstalled, get_called_class()
            );
        
            $result .= $tplRemoveService[TplApp::SECTION_CALL] . PHP_EOL . PHP_EOL .
            $tplRemoveService[TplApp::SECTION_CONTENT] . PHP_EOL;
        }
        
        $result .= $tplChangePort[TplApp::SECTION_CONTENT] . PHP_EOL;
        return $result;
    }
    
    public static function getMenuMongodbDebug()
    {
        global $neardLang;
    
        return TplApp::getActionRun(
            Action::DEBUG_MONGODB, array(BinMongodb::CMD_VERSION),
            array($neardLang->getValue(Lang::DEBUG_MONGODB_VERSION), TplAestan::GLYPH_DEBUG)
        ) . PHP_EOL;
    }
    
    public static function getActionChangeMongodbPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getMongodb()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallMongodbService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMongodb::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveMongodbService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMongodb::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
