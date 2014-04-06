<?php

class TplApp
{
    const ITEM_CAPTION = 0;
    const ITEM_GLYPH = 1;
    
    const SECTION_CALL = 0;
    const SECTION_CONTENT = 1;
    
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardConfig;
        
        return TplAestan::getSectionConfig() . PHP_EOL .
            self::getSectionServices() . PHP_EOL .
            TplAestan::getSectionMessages() . PHP_EOL .
            self::getSectionStartupAction() . PHP_EOL .
            TplAestan::getSectionMenuRightSettings() . PHP_EOL .
            TplAestan::getSectionMenuLeftSettings(APP_TITLE . ' ' . $neardConfig->getAppVersion()) . PHP_EOL .
            self::getSectionMenuRight() . PHP_EOL .
            self::getSectionMenuLeft() . PHP_EOL;
    }
    
    public static function processLight()
    {
        return TplAestan::getSectionConfig() . PHP_EOL .
            self::getSectionServices() . PHP_EOL .
            TplAestan::getSectionMessages() . PHP_EOL .
            self::getSectionStartupAction() . PHP_EOL;
    }
    
    public static function getSectionName($name, $args = array())
    {
        return ucfirst($name) . (!empty($args) ? '-' . md5(serialize($args)) : '');
    }
    
    public static function getSectionContent($name, $args = array(), $otherClass = false)
    {
        $baseMethod = 'get' . ucfirst($name);
        $args = $args == null ? array() : $args;
        $call = $otherClass !== false ? $otherClass . '::' . $baseMethod : array($this, $baseMethod);
        
        return '[' . self::getSectionName($name, $args) . ']' . PHP_EOL .
            call_user_func_array($call, $args);
    }
    
    public static function getActionRun($action, $args = array(), $item = array(), $waitUntilTerminated = true)
    {
        global $neardBs, $neardCore;
        $args = $args == null ? array() : $args;
        
        $argImp = '';
        foreach ($args as $arg) {
            $argImp .= ' ' . base64_encode($arg);
        }
        
        $result = 'Action: run; ' .
            'FileName: "' . $neardCore->getPhpCliSilentExe(true) . '"; ' .
            'Parameters: "' . Core::BOOTSTRAP_FILE . ' ' . $action . $argImp . '"; ' .
            'WorkingDir: "' . $neardBs->getCorePath(true) . '"';
        
        if (!empty($item)) {
            $result = 'Type: item; ' . $result .
                '; Caption: "' . $item[self::ITEM_CAPTION] . '"' .
                (!empty($item[self::ITEM_GLYPH]) ? '; Glyph: "' . $item[self::ITEM_GLYPH] . '"' : '');
        } elseif ($waitUntilTerminated) {
            $result .= '; Flags: waituntilterminated';
        }
        
        return $result;
    }
    
    public static function getActionMulti($action, $args = array(), $item = array(), $disabled = false, $otherClass = false)
    {
        $action = 'action' . ucfirst($action);
        $args = $args == null ? array() : $args;
        $sectionName = self::getSectionName($action, $args);
        
        //TODO: How managed disabled item??
        /*if ($disabled) {
            $call = 'Action: run; FileName: "%AeTrayMenuPath%core/libs/php/php-win.exe"; Parameters: "bootstrap.php switchApacheVersion 2.2.22"; WorkingDir: "%AeTrayMenuPath%core"; ';
        } else {*/
            $call = 'Action: multi; Actions: ' . $sectionName;
        //}
        
        if (!empty($item)) {
            $call = 'Type: item; ' . $call .
            '; Caption: "' . $item[self::ITEM_CAPTION] . '"' .
            (!empty($item[self::ITEM_GLYPH]) ? '; Glyph: "' . $item[self::ITEM_GLYPH] . '"' : '');
        } else {
            $call .= '; Flags: waituntilterminated';
        }
        
        return array($call, self::getSectionContent($action, $args, $otherClass));
    }
    
    public static function getActionExec()
    {
        return self::getActionRun(Action::EXEC, array(), array(), false);
    }
    
    public static function getMenu($caption, $menu, $otherClass = false)
    {
        $menu = 'menu' . ucfirst($menu);
        
        $call = 'Type: submenu; ' .
            'Caption: "' . $caption . '"; ' .
            'SubMenu: ' . self::getSectionName($menu) . '; ' .
            'Glyph: ' . TplAestan::GLYPH_FOLDER_CLOSE;
        
        return array($call, self::getSectionContent($menu, null, $otherClass));
    }
    
    private static function getSectionServices()
    {
        global $neardBins;
    
        $result = '[Services]' . PHP_EOL;
        foreach ($neardBins->getServices() as $service) {
            $result .= 'Name: ' . $service->getName() . PHP_EOL;
        }
    
        return $result;
    }
    
    private static function getSectionStartupAction()
    {
        global $neardBs, $neardBins;
    
        return '[StartupAction]' . PHP_EOL .
            self::getActionRun(Action::STARTUP) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            self::getActionExec() . PHP_EOL;
    }
    
    private static function getSectionMenuRight()
    {
        global $neardLang;
        
        $tplReload = TplAppReload::process();
        $tplBrowser = TplAppBrowser::process();
        $tplLang = TplAppLang::process();
        $tplLogsVerbose = TplAppLogsVerbose::process();
        $tplExit = TplAppExit::process();
        
        return
            // Items
            '[Menu.Right]' . PHP_EOL .
            TplApp::getActionRun(Action::ABOUT, null, array($neardLang->getValue(Lang::MENU_ABOUT), TplAestan::GLYPH_ABOUT)) . PHP_EOL . 
            TplAestan::getItemLink($neardLang->getValue(Lang::HELP), APP_GITHUB_HOME) . PHP_EOL .
            
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplReload[self::SECTION_CALL] . PHP_EOL .
            TplAppClearFolders::process() . PHP_EOL .
            $tplBrowser[self::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemConsole($neardLang->getValue(Lang::CONSOLE), TplAestan::GLYPH_CONSOLE) . PHP_EOL .
            
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplLang[self::SECTION_CALL] . PHP_EOL .
            $tplLogsVerbose[self::SECTION_CALL] . PHP_EOL .
            $tplExit[self::SECTION_CALL] . PHP_EOL .
        
            // Actions
            PHP_EOL . $tplReload[self::SECTION_CONTENT] . PHP_EOL .
            PHP_EOL . $tplBrowser[self::SECTION_CONTENT] . PHP_EOL .
            PHP_EOL . $tplLang[self::SECTION_CONTENT] .
            PHP_EOL . $tplLogsVerbose[self::SECTION_CONTENT] .
            PHP_EOL . $tplExit[self::SECTION_CONTENT] . PHP_EOL;
    }
    
    private static function getSectionMenuLeft()
    {
        global $neardBs, $neardLang;
        
        $tplNodejs = TplAppNodejs::process();
        $tplApache = TplAppApache::process();
        $tplPhp = TplAppPhp::process();
        $tplMysql = TplAppMysql::process();
        $tplMariadb = TplAppMariadb::process();
        $tplXlight = TplAppXlight::process();
        
        $tplGit = TplAppGit::process();
        $tplSvn = TplAppSvn::process();
        
        $tplHosts = TplAppHosts::process();
        $tplTools = TplAppTools::process();
        
        $tplServices = TplAppServices::process();
        
        $tplStatus = TplAppStatus::process();
    
        return
            // Items
            '[Menu.Left]' . PHP_EOL .
            TplAestan::getItemLink($neardLang->getValue(Lang::MENU_LOCALHOST), null, true) . PHP_EOL .
            TplAestan::getItemExplore($neardLang->getValue(Lang::MENU_WWW_DIRECTORY), $neardBs->getWwwPath()) . PHP_EOL .
            
            //// Bins menus
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplNodejs[self::SECTION_CALL] . PHP_EOL .
            $tplApache[self::SECTION_CALL] . PHP_EOL .
            $tplPhp[self::SECTION_CALL] . PHP_EOL .
            $tplMysql[self::SECTION_CALL] . PHP_EOL .
            $tplMariadb[self::SECTION_CALL] . PHP_EOL .
            $tplXlight[self::SECTION_CALL] . PHP_EOL .
            
            //// Git / SVN menus
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplGit[self::SECTION_CALL] . PHP_EOL .
            $tplSvn[self::SECTION_CALL] . PHP_EOL .
            
            //// Stuff menus
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplHosts[self::SECTION_CALL] . PHP_EOL .
            $tplTools[self::SECTION_CALL] . PHP_EOL .
            
            //// Services
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplServices[self::SECTION_CALL] .
            
            //// Put online/offline
            TplAestan::getItemSeparator() . PHP_EOL .
            $tplStatus[self::SECTION_CALL] . PHP_EOL .
            
            // Actions
            PHP_EOL . $tplNodejs[self::SECTION_CONTENT] .
            PHP_EOL . $tplApache[self::SECTION_CONTENT] .
            PHP_EOL . $tplPhp[self::SECTION_CONTENT] .
            PHP_EOL . $tplMysql[self::SECTION_CONTENT] .
            PHP_EOL . $tplMariadb[self::SECTION_CONTENT] .
            PHP_EOL . $tplXlight[self::SECTION_CONTENT] .
            PHP_EOL . $tplGit[self::SECTION_CONTENT] .
            PHP_EOL . $tplSvn[self::SECTION_CONTENT] .
            PHP_EOL . $tplHosts[self::SECTION_CONTENT] .
            PHP_EOL . $tplTools[self::SECTION_CONTENT] .
            PHP_EOL . $tplServices[self::SECTION_CONTENT] .
            PHP_EOL . $tplStatus[self::SECTION_CONTENT];
    }
}
