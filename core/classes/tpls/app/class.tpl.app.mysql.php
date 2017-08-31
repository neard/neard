<?php

class TplAppMysql
{
    const MENU = 'mysql';
    const MENU_VERSIONS = 'mysqlVersions';
    const MENU_SERVICE = 'mysqlService';
    const MENU_DEBUG = 'mysqlDebug';
    
    const ACTION_ENABLE = 'enableMysql';
    const ACTION_SWITCH_VERSION = 'switchMysqlVersion';
    const ACTION_CHANGE_PORT = 'changeMysqlPort';
    const ACTION_CHANGE_ROOT_PWD = 'changeMysqlRootPwd';
    const ACTION_INSTALL_SERVICE = 'installMysqlService';
    const ACTION_REMOVE_SERVICE = 'removeMysqlService';
    
    public static function process()
    {
        global $neardLang, $neardBins;
    
        return TplApp::getMenuEnable($neardLang->getValue(Lang::MYSQL), self::MENU, get_called_class(), $neardBins->getMysql()->isEnable());
    }
    
    public static function getMenuMysql()
    {
        global $neardBins, $neardLang, $neardTools;
        $resultItems = $resultActions = '';
        
        $isEnabled = $neardBins->getMysql()->isEnable();
        
        // Download
        $resultItems .= TplAestan::getItemLink(
            $neardLang->getValue(Lang::DOWNLOAD_MORE),
            Util::getWebsiteUrl('modules/mysql', '#releases'),
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
                $neardTools->getConsole()->getTabTitleMysql()
            ) . PHP_EOL;
    
            // Conf
            $resultItems .= TplAestan::getItemNotepad(basename($neardBins->getMysql()->getConf()), $neardBins->getMysql()->getConf()) . PHP_EOL;

            // Errors log
            $resultItems .= TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getMysql()->getErrorLog()) . PHP_EOL;
        }
    
        return $resultItems . PHP_EOL . $resultActions;
    }
    
    public static function getMenuMysqlVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getMysql()->getVersionList() as $version) {
            $tplSwitchMysqlVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $version == $neardBins->getMysql()->getVersion() ? TplAestan::GLYPH_CHECK : ''),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchMysqlVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchMysqlVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionEnableMysql($enable)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::ENABLE, array($neardBins->getMysql()->getName(), $enable)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionSwitchMysqlVersion($version)
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getMysql()->getName(), $version)) . PHP_EOL .
            TplApp::getActionExec() . PHP_EOL;
    }
    
    public static function getMenuMysqlService()
    {
        global $neardLang, $neardBins;
        
        $tplChangePort = TplApp::getActionMulti(
            self::ACTION_CHANGE_PORT, null,
            array($neardLang->getValue(Lang::MENU_CHANGE_PORT), TplAestan::GLYPH_NETWORK),
            false, get_called_class()
        );
        
        $isInstalled = $neardBins->getMysql()->getService()->isInstalled();
        
        $result = TplAestan::getItemActionServiceStart($neardBins->getMysql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceStop($neardBins->getMysql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemActionServiceRestart($neardBins->getMysql()->getService()->getName()) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
            TplApp::getActionRun(
                Action::CHECK_PORT, array($neardBins->getMysql()->getName(), $neardBins->getMysql()->getPort()),
                array(sprintf($neardLang->getValue(Lang::MENU_CHECK_PORT), $neardBins->getMysql()->getPort()), TplAestan::GLYPH_LIGHT)
            ) . PHP_EOL .
            $tplChangePort[TplApp::SECTION_CALL] . PHP_EOL;
        
        $tplChangeRootPwd = null;
        if ($isInstalled) {
            $tplChangeRootPwd = TplApp::getActionMulti(
                self::ACTION_CHANGE_ROOT_PWD, null,
                array($neardLang->getValue(Lang::MENU_CHANGE_ROOT_PWD), TplAestan::GLYPH_PASSWORD),
                !$isInstalled, get_called_class()
            );
            
            $result .= $tplChangeRootPwd[TplApp::SECTION_CALL] . PHP_EOL;
        }
        
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
        
        $result .= $tplChangePort[TplApp::SECTION_CONTENT] . PHP_EOL .
            ($tplChangeRootPwd != null ? $tplChangeRootPwd[TplApp::SECTION_CONTENT] . PHP_EOL : '');
        
        return $result;
    }
    
    public static function getMenuMysqlDebug()
    {
        global $neardLang;
    
        return TplApp::getActionRun(
            Action::DEBUG_MYSQL, array(BinMysql::CMD_VERSION),
            array($neardLang->getValue(Lang::DEBUG_MYSQL_VERSION), TplAestan::GLYPH_DEBUG)
        ) . PHP_EOL .
        TplApp::getActionRun(
            Action::DEBUG_MYSQL, array(BinMysql::CMD_VARIABLES),
            array($neardLang->getValue(Lang::DEBUG_MYSQL_VARIABLES), TplAestan::GLYPH_DEBUG)
        ) . PHP_EOL .
        TplApp::getActionRun(
            Action::DEBUG_MYSQL, array(BinMysql::CMD_SYNTAX_CHECK),
            array($neardLang->getValue(Lang::DEBUG_MYSQL_SYNTAX_CHECK), TplAestan::GLYPH_DEBUG)
        ) . PHP_EOL;
    }
    
    public static function getActionChangeMysqlPort()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_PORT, array($neardBins->getMysql()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionChangeMysqlRootPwd()
    {
        global $neardBins;
    
        return TplApp::getActionRun(Action::CHANGE_DB_ROOT_PWD, array($neardBins->getMysql()->getName())) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionInstallMysqlService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMysql::SERVICE_NAME, ActionService::INSTALL)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
    
    public static function getActionRemoveMysqlService()
    {
        return TplApp::getActionRun(Action::SERVICE, array(BinMysql::SERVICE_NAME, ActionService::REMOVE)) . PHP_EOL .
            TplAppReload::getActionReload();
    }
}
