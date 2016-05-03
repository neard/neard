<?php

class ActionClearFolders
{
    public function __construct($args)
    {
        global $neardBs, $neardCore;
        
        Util::clearFolder($neardBs->getTmpPath(), array('cachegrind', 'npm-cache', 'drush', 'wp-cli', 'mailhog'));
        Util::clearFolder($neardCore->getTmpPath());
    }

}
