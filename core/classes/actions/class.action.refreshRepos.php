<?php

class ActionRefreshRepos
{
    const GIT = 'git';
    const SVN = 'svn';
    
    public function __construct($args)
    {
        global $neardTools;
        
        Util::startLoading();
        if (isset($args[0]) && !empty($args[0])) {
            if ($args[0] == self::GIT) {
                $neardTools->getGit()->findRepos(false);
            } elseif ($args[0] == self::SVN) {
                $neardTools->getSvn()->findRepos(false);
            }
        }
    }

}
