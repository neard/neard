<?php

class ActionClearFolders
{
    public function __construct($args)
    {
        global $neardBs, $neardCore;

        Util::clearFolder($neardBs->getTmpPath(), array('cachegrind', 'composer', 'openssl', 'mailhog', 'npm-cache', 'pip', 'yarn'));
        Util::clearFolder($neardCore->getTmpPath());
    }
}
