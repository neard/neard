<?php

class TplConsole
{
    const ICON_APP = 'app.ico';
    const ICON_POWERSHELL = 'powershell.ico';
    const ICON_PEAR = 'pear.ico';
    const ICON_DB = 'db.ico';
    const ICON_GIT = 'git.ico';
    const ICON_SVN = 'svn.ico';
    const ICON_NODEJS = 'nodejs.ico';
    const ICON_COMPOSER = 'composer.ico';
    const ICON_PHPMETRICS = 'phpmetrics.ico';
    const ICON_PHPUNIT = 'phpunit.ico';
    const ICON_DRUSH = 'drush.ico';
    const ICON_WPCLI = 'wpcli.ico';
    const ICON_PYTHON = 'python.ico';
    const ICON_RUBY = 'ruby.ico';
    const ICON_YARN = 'yarn.ico';
    const ICON_PERL = 'perl.ico';
    
    private function __construct()
    {
    }
    
    public static function process()
    {
        global $neardTools;
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
                self::getIncrStr(3) . '<color id="0" r="39" g="40" b="34"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="1" r="88" g="194" b="229"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="2" r="88" g="194" b="229"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="3" r="198" g="197" b="254"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="4" r="168" g="125" b="184"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="5" r="243" g="4" b="75"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="6" r="243" g="4" b="75"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="7" r="238" g="238" b="238"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="8" r="124" g="124" b="124"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="9" r="3" g="131" b="245"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="10" r="141" g="208" b="6"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="11" r="88" g="194" b="229"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="12" r="168" g="125" b="184"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="13" r="243" g="4" b="75"/>' . PHP_EOL .
                self::getIncrStr(3) . '<color id="14" r="204" g="204" b="129"/>' . PHP_EOL .
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
                self::getTabCmdSection() .
                self::getTabPowerShellSection() .
                self::getTabPearSection() .
                self::getTabMysqlSection() .
                self::getTabMariadbSection() .
                self::getTabMongodbSection() .
                self::getTabPostgresqlSection() .
                self::getTabGitSection() .
                self::getTabSvnSection() .
                self::getTabNodejsSection() .
                self::getTabComposerSection() .
                self::getTabPerlSection() .
                self::getTabPhpMetricsSection() .
                self::getTabPhpUnitSection() .
                self::getTabDrushSection() .
                self::getTabWpCliSection() .
                self::getTabPythonSection() .
                self::getTabRubySection() .
                self::getTabYarnSection() .
            self::getIncrStr(1) . '</tabs>';
    }
    
    private static function getTabCmdSection()
    {
        global $neardBs, $neardTools;
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleDefault(),
            self::ICON_APP,
            $neardTools->getConsole()->getShell(),
            $neardBs->getRootPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPowerShellSection()
    {
        global $neardBs, $neardTools;
        
        $powerShellPath = Util::getPowerShellPath();
        if ($powerShellPath !== false) {
            return self::getTab(
                $neardTools->getConsole()->getTabTitlePowershell(),
                self::ICON_POWERSHELL,
                $powerShellPath,
                $neardBs->getRootPath()
            ) . PHP_EOL;
        }
    }
    
    private static function getTabPearSection()
    {
        global $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getPhp()->getPearExe() . '&quot; -V');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePear(),
            self::ICON_PEAR,
            $shell,
            $neardBins->getPhp()->getPearPath()
        ) . PHP_EOL;
    }
    
    private static function getTabMysqlSection()
    {
        global $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getMysql()->getCliExe() . '&quot; -u' .
            $neardBins->getMysql()->getRootUser() .
            ($neardBins->getMysql()->getRootPwd() ? ' -p' : ''));
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleMysql(),
            self::ICON_DB,
            $shell,
            $neardBins->getMysql()->getCurrentPath()
        ) . PHP_EOL;
    }
    
    private static function getTabMariadbSection()
    {
        global $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getMariadb()->getCliExe() . '&quot; -u' .
            $neardBins->getMariadb()->getRootUser() .
            ($neardBins->getMariadb()->getRootPwd() ? ' -p' : ''));
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleMariadb(),
            self::ICON_DB,
            $shell,
            $neardBins->getMariadb()->getCurrentPath()
        ) . PHP_EOL;
    }
    
    private static function getTabMongodbSection()
    {
        global $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getMongodb()->getCliExe() . '&quot;');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleMongodb(),
            self::ICON_DB,
            $shell,
            $neardBins->getMongodb()->getCurrentPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPostgresqlSection()
    {
        global $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getPostgresql()->getCliExe() . '&quot; -U ' .
            $neardBins->getPostgresql()->getRootUser() . ' -d postgres');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePostgresql(),
            self::ICON_DB,
            $shell,
            $neardBins->getPostgresql()->getCurrentPath()
        ) . PHP_EOL;
    }
    
    private static function getTabSvnSection()
    {
        global $neardBs, $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getSvn()->getExe() . '&quot; --version');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleSvn(),
            self::ICON_SVN,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabGitSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getGit()->getExe() . '&quot; --version');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleGit(),
            self::ICON_GIT,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabNodejsSection()
    {
        global $neardBs, $neardBins, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardBins->getNodejs()->getLaunch(). '&quot;');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleNodejs(),
            self::ICON_NODEJS,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabComposerSection()
    {
        global $neardBs, $neardTools;
    
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getComposer()->getExe() . '&quot; -V');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleComposer(),
            self::ICON_COMPOSER,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPhpMetricsSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getPhpMetrics()->getExe() . '&quot; --version');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePhpMetrics(),
            self::ICON_PHPMETRICS,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPhpUnitSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getPhpUnit()->getExe() . '&quot; --version');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePhpUnit(),
            self::ICON_PHPUNIT,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabDrushSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getDrush()->getExe() . '&quot; version');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleDrush(),
            self::ICON_DRUSH,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabWpCliSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getWpCli()->getExe() . '&quot; --info');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleWpCli(),
            self::ICON_WPCLI,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPythonSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getPython()->getExe() . '&quot; -V');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePython(),
            self::ICON_PYTHON,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabRubySection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getRuby()->getExe() . '&quot; -v');
    
        return self::getTab(
            $neardTools->getConsole()->getTabTitleRuby(),
            self::ICON_RUBY,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabYarnSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getYarn()->getExe() . '&quot; -V');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitleYarn(),
            self::ICON_YARN,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTabPerlSection()
    {
        global $neardBs, $neardTools;
        
        $shell = $neardTools->getConsole()->getShell('&quot;' . $neardTools->getPerl()->getExe() . '&quot; -v');
        
        return self::getTab(
            $neardTools->getConsole()->getTabTitlePerl(),
            self::ICON_PERL,
            $shell,
            $neardBs->getWwwPath()
        ) . PHP_EOL;
    }
    
    private static function getTab($title, $icon, $shell, $initDir)
    {
        global $neardCore;
        return self::getIncrStr(2) . '<tab title="' . $title . '" icon="' . $neardCore->getIconsPath(false) . '/' . $icon . '" use_default_icon="0">' . PHP_EOL .
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
