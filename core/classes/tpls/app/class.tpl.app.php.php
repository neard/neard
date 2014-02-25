<?php

class TplAppPhp
{
    const MENU = 'php';
    const MENU_VERSIONS = 'phpVersions';
    const MENU_SETTINGS = 'phpSettings';
    const MENU_EXTENSIONS = 'phpExtensions';
    
    const ACTION_SWITCH_VERSION = 'switchPhpVersion';
    const ACTION_SWITCH_SETTING = 'switchPhpSetting';
    const ACTION_SWITCH_EXTENSION = 'switchPhpExtension';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::PHP), self::MENU, get_called_class());
    }
    
    public static function getMenuPhp()
    {
        global $neardBins, $neardLang;
        
        $tplVersions = TplApp::getMenu($neardLang->getValue(Lang::VERSIONS), self::MENU_VERSIONS, get_called_class());
        $tplSettings = TplApp::getMenu($neardLang->getValue(Lang::SETTINGS), self::MENU_SETTINGS, get_called_class());
        $tplExtensions = TplApp::getMenu($neardLang->getValue(Lang::EXTENSIONS), self::MENU_EXTENSIONS, get_called_class());
        
        return
        
            // Items
            $tplVersions[TplApp::SECTION_CALL] . PHP_EOL .
            $tplSettings[TplApp::SECTION_CALL] . PHP_EOL .
            $tplExtensions[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemNotepad(basename($neardBins->getPhp()->getApacheConf()), $neardBins->getPhp()->getApacheConf()) . PHP_EOL .
            TplAestan::getItemNotepad($neardLang->getValue(Lang::MENU_ERROR_LOGS), $neardBins->getPhp()->getErrorLog()) . PHP_EOL . PHP_EOL .
            
            // Actions
            $tplVersions[TplApp::SECTION_CONTENT] . PHP_EOL . PHP_EOL .
            $tplSettings[TplApp::SECTION_CONTENT] .
            $tplExtensions[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuPhpVersions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
        
        foreach ($neardBins->getPhp()->getVersionList() as $version) {
            $glyph = '';
            $apachePhpModule = $neardBins->getPhp()->getApacheModule($neardBins->getApache()->getVersion(), $version);
            if ($apachePhpModule === false) {
                $glyph = TplAestan::GLYPH_WARNING;
            } elseif ($version == $neardBins->getPhp()->getVersion()) {
                $glyph = TplAestan::GLYPH_CHECK;
            }
            
            $tplSwitchPhpVersion = TplApp::getActionMulti(
                self::ACTION_SWITCH_VERSION, array($version),
                array($version, $glyph),
                false, get_called_class()
            );
            
            // Item
            $items .= $tplSwitchPhpVersion[TplApp::SECTION_CALL] . PHP_EOL;
        
            // Action
            $actions .= PHP_EOL . $tplSwitchPhpVersion[TplApp::SECTION_CONTENT];
        }
        
        return $items . $actions;
    }
    
    public static function getActionSwitchPhpVersion($version)
    {
        global $neardBins;
    
        //TODO: Check stop apache service
        return //TplService::getActionStop(BinApache::SERVICE_NAME) . PHP_EOL .
            TplApp::getActionRun(Action::SWITCH_VERSION, array($neardBins->getPhp()->getName(), $version)) . PHP_EOL;
            /*TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;*/
    }
    
    public static function getMenuPhpSettings()
    {
        global $neardLang, $neardBins;
        
        $menuItems = '';
        $menuActions = '';
        foreach ($neardBins->getPhp()->getSettings() as $key => $value) {
            if (is_array($value)) {
                $menuItems .= 'Type: submenu; ' .
                    'Caption: "' . $key . '"; ' .
                    'SubMenu: MenuPhpSetting-' . md5($key) . '; ' .
                    'Glyph: ' . TplAestan::GLYPH_FOLDER_CLOSE . PHP_EOL;
            } else {
                $settingEnabled = $neardBins->getPhp()->isSettingActive($value);
                $tplSwitchPhpSetting = TplApp::getActionMulti(
                    self::ACTION_SWITCH_SETTING, array($value, $settingEnabled),
                    array($key, $settingEnabled ? TplAestan::GLYPH_CHECK : ''),
                    false, get_called_class()
                );
                
                $menuItems .= $tplSwitchPhpSetting[TplApp::SECTION_CALL] . PHP_EOL;
                $menuActions .= $tplSwitchPhpSetting[TplApp::SECTION_CONTENT];
            }
        }
        
        $submenusItems = '';
        $submenusActions = '';
        $submenuKeys = self::getSubmenuPhpSettings();
        foreach ($submenuKeys as $submenuKey) {
            $submenusItems .= PHP_EOL . '[MenuPhpSetting-' . md5($submenuKey) . ']' .
                PHP_EOL . self::getSubmenuPhpSettings($submenuKey);
            
            $submenusActions .= self::getSubmenuPhpSettings($submenuKey, array(), array(), false);
        }
        
        return $menuItems . $submenusItems . PHP_EOL . $menuActions . $submenusActions;
    }
    
    private static function getSubmenuPhpSettings($passThr = array(), $result = array(), $settings = array(), $sectionCall = true)
    {
        global $neardBins;
        $settings = empty($settings) ? $neardBins->getPhp()->getSettings() : $settings;
    
        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                if (is_array($passThr)) {
                    array_push($result, $key);
                    $result = self::getSubmenuPhpSettings($passThr, $result, $value);
                } else {
                    $result = is_array($result) ? '' : $result;
                    if ($key == $passThr) {
                        foreach ($value as $key2 => $value2) {
                            if (is_array($value2) && $sectionCall) {
                                $result .= 'Type: submenu; ' .
                                    'Caption: "' . $key2 . '"; ' .
                                    'SubMenu: MenuPhpSetting-' . md5($key2) . '; ' .
                                    'Glyph: ' . TplAestan::GLYPH_FOLDER_CLOSE . PHP_EOL;
                            } elseif (!is_array($value2)) {
                                $settingEnabled = $neardBins->getPhp()->isSettingActive($value2);
                                $tplSwitchPhpSetting = TplApp::getActionMulti(
                                    self::ACTION_SWITCH_SETTING, array($value2, $settingEnabled),
                                    array($key2, $settingEnabled ? TplAestan::GLYPH_CHECK : ''),
                                    false, get_called_class()
                                );
                                
                                if ($sectionCall) {
                                    $result .= $tplSwitchPhpSetting[TplApp::SECTION_CALL] . PHP_EOL;
                                } else {
                                    $result .= $tplSwitchPhpSetting[TplApp::SECTION_CONTENT] . PHP_EOL;
                                }
                            }
                        }
                    } else {
                        $result .= self::getSubmenuPhpSettings($passThr, null, $value, $sectionCall);
                    }
                }
            }
        }
    
        return $result;
    }
    
    public static function getActionSwitchPhpSetting($extension, $enabled)
    {
        global $neardBins;
        
        $switch = $enabled ? ActionSwitchPhpExtension::SWITCH_OFF : ActionSwitchPhpExtension::SWITCH_ON;
        return TplApp::getActionRun(Action::SWITCH_PHP_PARAM, array($extension, $switch)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL .
            TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL;
    }
    
    public static function getMenuPhpExtensions()
    {
        global $neardBins;
        $items = '';
        $actions = '';
    
        foreach ($neardBins->getPhp()->getExtensions() as $extension => $enabled) {
            $tplSwitchPhpExtension = TplApp::getActionMulti(
                self::ACTION_SWITCH_EXTENSION, array($extension, $enabled),
                array($extension, ($enabled == ActionSwitchPhpExtension::SWITCH_ON ? TplAestan::GLYPH_CHECK : '')),
                false, get_called_class()
            );
    
            // Item
            $items .= $tplSwitchPhpExtension[TplApp::SECTION_CALL] . PHP_EOL;
    
            // Action
            $actions .= PHP_EOL . $tplSwitchPhpExtension[TplApp::SECTION_CONTENT];
        }
    
        return $items . $actions;
    }
    
    public static function getActionSwitchPhpExtension($extension, $enabled)
    {
        global $neardBins;
    
        $switch = $enabled ? ActionSwitchApacheModule::SWITCH_OFF : ActionSwitchApacheModule::SWITCH_ON;
        return TplApp::getActionRun(Action::SWITCH_PHP_EXTENSION, array($extension, $switch)) . PHP_EOL .
        TplAppReload::getActionReload() . PHP_EOL .
        TplService::getActionRestart(BinApache::SERVICE_NAME) . PHP_EOL;
    }
    
}
