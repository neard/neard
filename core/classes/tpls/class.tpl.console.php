<?php

class TplConsole
{
    const ICON_APP = 'app.ico';
    const ICON_PEAR = 'pear.ico';
    const ICON_DB = 'db.ico';
    const ICON_GIT = 'git.ico';
    const ICON_SVN = 'svn.ico';
    const ICON_NODEJS = 'nodejs.ico';
    const ICON_COMPOSER = 'composer.ico';
    
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardConfig, $neardTools;
        $result = '<?xml version="1.0"?>' . PHP_EOL . '<settings>' . PHP_EOL .
            self::getConsoleSection() . PHP_EOL .
            self::getAppearanceSection() . PHP_EOL .
            self::getBehaviorSection() . PHP_EOL .
            self::getHotkeysSection() . PHP_EOL .
            self::getMouseSection() . PHP_EOL .
            self::getTabsSection() . PHP_EOL .
            '</settings>';
            
        file_put_contents($neardTools->getConsole()->getConf(), $result);
    }
    
    private static function getConsoleSection()
    {
        global $neardBs, $neardTools;
        
        $sectionConsoleStart = self::getIncrStr(1) . '<console ' . 
            'change_refresh="10" ' .
            'refresh="100" ' .
            'rows="' . $neardTools->getConsole()->getRows() . '" ' .
            'columns="' . $neardTools->getConsole()->getCols() . '" ' .
            'buffer_rows="2048" ' .
            'buffer_columns="0" ' .
            'shell="" ' .
            'init_dir="' . $neardBs->getRootPath() . '" ' .
            'start_hidden="0" ' .
            'save_size="0">' . PHP_EOL;
        
        $sectionColors = self::getIncrStr(2) . '<colors>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="0" r="0" g="0" b="0"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="1" r="0" g="0" b="128"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="2" r="0" g="150" b="0"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="3" r="0" g="150" b="150"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="4" r="170" g="25" b="25"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="5" r="128" g="0" b="128"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="6" r="128" g="128" b="0"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="7" r="192" g="192" b="192"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="8" r="128" g="128" b="128"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="9" r="0" g="100" b="255"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="10" r="0" g="255" b="0"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="11" r="0" g="255" b="255"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="12" r="255" g="50" b="50"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="13" r="255" g="0" b="255"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="14" r="255" g="255" b="0"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="15" r="255" g="255" b="255"/>' . PHP_EOL .
            self::getIncrStr(2) . '</colors>' . PHP_EOL;
        
        $sectionConsoleEnd = self::getIncrStr(1) . '</console>';
        
        return $sectionConsoleStart . $sectionColors . $sectionConsoleEnd;
    }
    
    private static function getAppearanceSection()
    {
        $sectionFont = self::getIncrStr(2) . '<font name="Courier New" size="10" bold="0" italic="0" smoothing="0">' . PHP_EOL .
            self::getIncrStr(3) . '<color use="0" r="0" g="255" b="0"/>' . PHP_EOL .
            self::getIncrStr(2) . '</font>';
        
        $windowSection = self::getIncrStr(2) . '<window ' .
            'title="Console" ' .
            'icon="" ' .
            'use_tab_icon="1" ' .
            'use_console_title="0" ' .
            'show_cmd="0" ' .
            'show_cmd_tabs="0" ' .
            'use_tab_title="1" ' .
            'trim_tab_titles="20" ' .
            'trim_tab_titles_right="0"/>';
        
        $controlsSection = self::getIncrStr(2) . '<controls ' .
            'show_menu="0" ' .
            'show_toolbar="1" ' .
            'show_statusbar="1" ' .
            'show_tabs="1" ' .
            'hide_single_tab="1" ' .
            'show_scrollbars="1" ' .
            'flat_scrollbars="0" ' .
            'tabs_on_bottom="0"/>';
        
        $stylesSection = self::getIncrStr(2) . '<styles caption="1" resizable="1" taskbar_button="1" border="1" inside_border="2" tray_icon="0">' . PHP_EOL .
            self::getIncrStr(3) . '<selection_color r="255" g="255" b="255"/>' . PHP_EOL .
            self::getIncrStr(2) . '</styles>';
            
        $positionSection = self::getIncrStr(2) . '<position ' .
            'x="-1" ' .
            'y="-1" ' .
            'dock="-1" ' .
            'snap="0" ' .
            'z_order="0" ' .
            'save_position="0"/>';
        
        $transparencySection = self::getIncrStr(2) . '<transparency ' .
            'type="1" ' .
            'active_alpha="240" ' .
            'inactive_alpha="225" ' .
            'r="0" ' .
            'g="0" ' .
            'b="0"/>';
        
        return self::getIncrStr(1) . '<appearance>' . PHP_EOL .
                $sectionFont . PHP_EOL .
                $windowSection . PHP_EOL .
                $controlsSection . PHP_EOL .
                $stylesSection . PHP_EOL .
                $positionSection . PHP_EOL .
                $transparencySection . PHP_EOL .
            self::getIncrStr(1) . '</appearance>';
    }
    
    private static function getBehaviorSection()
    {
        $sectionCopyPaste = self::getIncrStr(2) . '<copy_paste ' .
            'copy_on_select="0" ' .
            'clear_on_copy="1" ' .
            'no_wrap="1" ' .
            'trim_spaces="1" ' .
            'copy_newline_char="0" ' .
            'sensitive_copy="1"/>';
        
        $sectionScroll = self::getIncrStr(2) . '<scroll page_scroll_rows="0"/>';
        $sectionTabHighlight = self::getIncrStr(2) . '<tab_highlight flashes="3" stay_highligted="1"/>';
        
        return self::getIncrStr(1) . '<behavior>' . PHP_EOL .
                $sectionCopyPaste . PHP_EOL .
                $sectionScroll . PHP_EOL .
                $sectionTabHighlight . PHP_EOL .
            self::getIncrStr(1) . '</behavior>';
    }
    
    private static function getHotkeysSection()
    {
        return self::getIncrStr(1) . '<hotkeys use_scroll_lock="0">' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="83" command="settings"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="112" command="help"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="1" extended="0" code="115" command="exit"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="112" command="newtab1"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="113" command="newtab2"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="114" command="newtab3"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="115" command="newtab4"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="116" command="newtab5"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="117" command="newtab6"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="118" command="newtab7"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="119" command="newtab8"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="120" command="newtab9"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="121" command="newtab10"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="49" command="switchtab1"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="50" command="switchtab2"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="51" command="switchtab3"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="52" command="switchtab4"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="53" command="switchtab5"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="54" command="switchtab6"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="55" command="switchtab7"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="56" command="switchtab8"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="57" command="switchtab9"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="48" command="switchtab10"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="9" command="nexttab"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="1" alt="0" extended="0" code="9" command="prevtab"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="87" command="closetab"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="0" code="82" command="renametab"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="1" code="45" command="copy"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="0" alt="0" extended="1" code="46" command="clear_selection"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="1" alt="0" extended="1" code="45" command="paste"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="stopscroll"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollrowup"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollrowdown"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollpageup"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollpagedown"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollcolleft"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollcolright"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollpageleft"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="scrollpageright"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="1" shift="1" alt="0" extended="0" code="112" command="dumpbuffer"/>' . PHP_EOL .
                self::getIncrStr(2) . '<hotkey ctrl="0" shift="0" alt="0" extended="0" code="0" command="activate"/>' . PHP_EOL .
            self::getIncrStr(1) . '</hotkeys>';
    }
    
    private static function getMouseSection()
    {
        return self::getIncrStr(1) . '<mouse>' . PHP_EOL .
                self::getIncrStr(2) . '<actions>' . PHP_EOL .
                    self::getIncrStr(3) . '<action ctrl="0" shift="0" alt="0" button="1" name="copy"/>' . PHP_EOL .
                    self::getIncrStr(3) . '<action ctrl="0" shift="1" alt="0" button="1" name="select"/>' . PHP_EOL .
                    self::getIncrStr(3) . '<action ctrl="0" shift="0" alt="0" button="3" name="paste"/>' . PHP_EOL .
                    self::getIncrStr(3) . '<action ctrl="1" shift="0" alt="0" button="1" name="drag"/>' . PHP_EOL .
                    self::getIncrStr(3) . '<action ctrl="0" shift="0" alt="0" button="2" name="menu"/>' . PHP_EOL .
                self::getIncrStr(2) . '</actions>' . PHP_EOL .
            self::getIncrStr(1) . '</mouse>';
    }
    
    private static function getTabsSection()
    {
        return self::getIncrStr(1) . '<tabs>' . PHP_EOL .
                self::getTabShellSections() . PHP_EOL .
                self::getTabPearSection() . PHP_EOL .
                self::getTabMysqlSection() . PHP_EOL .
                self::getTabMariadbSection() . PHP_EOL .
                self::getTabGitSection() . PHP_EOL .
                self::getTabSvnSection() . PHP_EOL .
                self::getTabNodejsSection() . PHP_EOL .
                self::getTabComposerSection() . PHP_EOL .
            self::getIncrStr(1) . '</tabs>';
    }
    
    private static function getTabShellSections()
    {
        global $neardBs, $neardCore, $neardTools;
        $default = '';
        $result = '';
        
        foreach ($neardTools->getConsole()->getShellList() as $name => $path) {
            if ($neardTools->getConsole()->getShell() == $path) {
                $default = self::getTab(
                    $neardTools->getConsole()->getTabTitleDefault() . ' ' . $name,
                    self::ICON_APP,
                    Util::formatWindowsPath($path),
                    $neardBs->getRootPath()
                );
            } else {
                $result .= PHP_EOL . self::getTab(
                    $neardTools->getConsole()->getTabTitleDefault() . ' ' . $name,
                    self::ICON_APP,
                    Util::formatWindowsPath($path),
                    $neardBs->getRootPath()
                );
            }
        }
        
        return $default . $result;
    }
    
    private static function getTabPearSection()
    {
        global $neardCore, $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getCmdShell() . ' ' .
            '&quot;' . $neardBins->getPhp()->getPearExe() . '&quot; -V';
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePear(),
            self::ICON_PEAR,
            Util::formatWindowsPath($shell),
            $neardBins->getPhp()->getPearPath()
        );
    }
    
    private static function getTabMysqlSection()
    {
        global $neardCore, $neardBins, $neardTools;
    
        $shell = $neardTools->getConsole()->getCmdShell() . ' ' .
            '&quot;' . $neardBins->getMysql()->getCliExe() . '&quot; -uroot';
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleMysql(),
            self::ICON_DB,
            Util::formatWindowsPath($shell),
            $neardBins->getMysql()->getCurrentPath()
        );
    }
    
    private static function getTabMariadbSection()
    {
        global $neardCore, $neardBins, $neardTools;
    
        $shell = $neardTools->getConsole()->getCmdShell() . ' ' .
            '&quot;' . $neardBins->getMariadb()->getCliExe() . '&quot; -uroot';
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleMariadb(),
            self::ICON_DB,
            Util::formatWindowsPath($shell),
            $neardBins->getMariadb()->getCurrentPath()
        );
    }
    
    private static function getTabGitSection()
    {
        global $neardBs, $neardTools;
        
        $gitShell = $neardTools->getGit()->getBash();
        if (Util::endWith($neardTools->getGit()->getBash(), '.exe')) {
            $gitShell = $neardTools->getGit()->getCurrentPath() . '/bin/sh.exe';
        }
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleGit(),
            self::ICON_GIT,
            Util::formatWindowsPath('&quot;' . $gitShell . '&quot;'),
            $neardBs->getWwwPath()
        );
    }
    
    private static function getTabSvnSection()
    {
        global $neardBs, $neardTools;
        
        $svnVersion = Batch::getSvnVersion();
        $shell = $neardTools->getConsole()->getCmdShell() .
            (!empty($svnVersion) ? ' echo ' . $svnVersion[0] . ' &amp; echo ' . $svnVersion[1] : null);
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleSvn(),
            self::ICON_SVN,
            Util::formatWindowsPath($shell),
            $neardBs->getWwwPath()
        );
    }
    
    private static function getTabNodejsSection()
    {
        global $neardBs, $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getCmdShell() . ' ' .
            '&quot;' . $neardBins->getNodejs()->getLaunch() . '&quot;';
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleNodejs(),
            self::ICON_NODEJS,
            Util::formatWindowsPath($shell),
            $neardBs->getWwwPath()
        );
    }
    
    private static function getTabComposerSection()
    {
        global $neardBs, $neardTools;
    
        $shell = $neardTools->getConsole()->getCmdShell() . ' ' .
            '&quot;' . $neardTools->getComposer()->getExe() . '&quot; -V';
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleComposer(),
            self::ICON_COMPOSER,
            Util::formatWindowsPath($shell),
            $neardBs->getWwwPath()
        );
    }
    
    private static function getTab($title, $icon, $shell, $initDir)
    {
        return self::getIncrStr(2) . '<tab title="' . $title . '" icon="icons/' . $icon . '" use_default_icon="0">' . PHP_EOL .
                self::getIncrStr(3) . '<console shell="' . $shell . '" init_dir="' . $initDir . '" run_as_user="0" user=""/>' . PHP_EOL .
                self::getIncrStr(3) . '<cursor style="0" r="255" g="255" b="255"/>' . PHP_EOL .
                self::getIncrStr(3) . '<background type="0" r="0" g="0" b="0">' . PHP_EOL .
                    self::getIncrStr(4) . '<image file="" relative="0" extend="0" position="0">' . PHP_EOL .
                         self::getIncrStr(5) . '<tint opacity="0" r="0" g="0" b="0"/>' . PHP_EOL .
                    self::getIncrStr(4) . '</image>' . PHP_EOL .
                self::getIncrStr(3) . '</background>' . PHP_EOL .
            self::getIncrStr(2) . '</tab>';
    }
    
    private static function getIncrStr($size = 1)
    {
        $result = '';
        for ($i = 0; $i <= $size; $i++) {
            $result .= RETURN_TAB;
        }
        return $result;
    }
}
