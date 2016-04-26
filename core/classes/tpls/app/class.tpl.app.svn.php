<?php

class TplAppSvn
{
    const MENU = 'svn';
    const MENU_REPOS = 'svnRepos';
    
    const ACTION_REFRESH_REPOS = 'refreshSvnRepos';
    const ACTION_REFRESH_REPOS_STARTUP = 'refreshSvnReposStartup';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::SVN), self::MENU, get_called_class());
    }
    
    public static function getMenuSvn()
    {
        global $neardLang, $neardTools;
        
        $tplRepos = TplApp::getMenu($neardLang->getValue(Lang::REPOS), self::MENU_REPOS, get_called_class());
        $emptyRepos = count(explode(PHP_EOL, $tplRepos[TplApp::SECTION_CONTENT])) == 2;
        $isScanStartup = $neardTools->getSvn()->isScanStartup();
        
        $tplRefreshRepos = TplApp::getActionMulti(
            self::ACTION_REFRESH_REPOS, null,
            array($neardLang->getValue(Lang::MENU_REFRESH_REPOS), TplAestan::GLYPH_RELOAD),
            false, get_called_class()
        );
        $tplRefreshReposStartup = TplApp::getActionMulti(
            self::ACTION_REFRESH_REPOS_STARTUP, array($isScanStartup ? Config::DISABLED : Config::ENABLED),
            array($neardLang->getValue(Lang::MENU_SCAN_REPOS_STARTUP), $isScanStartup ? TplAestan::GLYPH_CHECK : ''),
            false, get_called_class()
        );
        
        return TplAestan::getItemConsole(
                $neardLang->getValue(Lang::SVN_CONSOLE),
                TplAestan::GLYPH_SVN,
                $neardTools->getConsole()->getTabTitleSvn()
            ) . PHP_EOL .
            TplAestan::getItemSeparator() . PHP_EOL .
        
            // Items
            (!$emptyRepos ? $tplRepos[TplApp::SECTION_CALL] . PHP_EOL : '') .
            $tplRefreshRepos[TplApp::SECTION_CALL] . PHP_EOL .
            $tplRefreshReposStartup[TplApp::SECTION_CALL] . PHP_EOL .
            
            // Actions
            (!$emptyRepos ? $tplRepos[TplApp::SECTION_CONTENT] . PHP_EOL : PHP_EOL) .
            $tplRefreshRepos[TplApp::SECTION_CONTENT] . PHP_EOL .
            $tplRefreshReposStartup[TplApp::SECTION_CONTENT];
    }
    
    public static function getMenuSvnRepos()
    {
        global $neardTools;
        $result = '';
        
        foreach ($neardTools->getSvn()->findRepos() as $repo) {
            $result .= TplAestan::getItemConsole(
                basename($repo),
                TplAestan::GLYPH_SVN,
                $neardTools->getConsole()->getTabTitleSvn(),
                $neardTools->getConsole()->getTabTitleSvn($repo),
                $repo
            ) . PHP_EOL;
        }
        
        return $result;
    }
    
    public static function getActionRefreshSvnRepos()
    {
        return TplApp::getActionRun(Action::REFRESH_REPOS, array(ActionRefreshRepos::SVN)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
    public static function getActionRefreshSvnReposStartup($scanStartup)
    {
        return TplApp::getActionRun(Action::REFRESH_REPOS_STARTUP, array(ActionRefreshRepos::SVN, $scanStartup)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
}
