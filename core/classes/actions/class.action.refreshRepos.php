<?php

class ActionRefreshRepos
{
    const GIT = 'git';
    
    public function __construct($args)
    {
        global $neardBins, $neardTools;
        
        Util::startLoading();
        if (isset($args[0]) && !empty($args[0])) {
            if ($args[0] == self::GIT) {
                $neardTools->getGit()->findRepos(false);
            }
        }
    }

}
