<?php

class TplAppGit
{
    const MENU = 'git';
    const MENU_REPOS = 'gitRepos';
    
    const ACTION_REFRESH_REPOS = 'refreshGitRepos';
    const ACTION_REFRESH_REPOS_STARTUP = 'refreshGitReposStartup';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::GIT), self::MENU, get_called_class());
    }
    
    public static function getMenuGit()
    {
        global $neardLang, $neardTools;
        
        $tplRepos = TplApp::getMenu($neardLang->getValue(Lang::REPOS), self::MENU_REPOS, get_called_class());
        $emptyRepos = count(explode(PHP_EOL, $tplRepos[TplApp::SECTION_CONTENT])) == 2;
        $isScanStartup = $neardTools->getGit()->isScanStartup();
        
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
                $neardLang->getValue(Lang::GIT_CONSOLE),
                TplAestan::GLYPH_GIT,
                $neardTools->getConsole()->getTabTitleGit()
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
    
    public static function getMenuGitRepos()
    {
        global $neardTools;
        $result = '';
        
        foreach ($neardTools->getGit()->findRepos() as $repo) {
            $result .= TplAestan::getItemConsole(
                basename($repo),
                TplAestan::GLYPH_GIT,
                $neardTools->getConsole()->getTabTitleGit(),
                $neardTools->getConsole()->getTabTitleGit($repo),
                $repo
            ) . PHP_EOL;
        }
        
        return $result;
    }
    
    public static function getActionRefreshGitRepos()
    {
        return TplApp::getActionRun(Action::REFRESH_REPOS, array(ActionRefreshRepos::GIT)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
    
    public static function getActionRefreshGitReposStartup($scanStartup)
    {
        return TplApp::getActionRun(Action::REFRESH_REPOS_STARTUP, array(ActionRefreshRepos::GIT, $scanStartup)) . PHP_EOL .
            TplAppReload::getActionReload() . PHP_EOL;
    }
}
