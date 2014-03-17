<?php

class ActionClearFolders
{
    public function __construct($args)
    {
        global $neardBs, $neardCore;
        
        Util::clearFolder($neardBs->getTmpPath(), array('placeholder', 'cachegrind'));
        Util::clearFolder($neardCore->getTmpPath(), array('placeholder'));
    }

}
