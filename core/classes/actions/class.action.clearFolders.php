<?php

class ActionClearFolders
{
    public function __construct($args)
    {
        global $neardBs, $neardCore;
        
        Util::clearFolder($neardBs->getTmpPath(), array('cachegrind', 'composer', 'drush', 'openssl', 'mailhog', 'npm-cache', 'pip', 'wp-cli', 'yarn'));
        Util::clearFolder($neardCore->getTmpPath());
    }
}
