<?php

class ActionCheckPort
{
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $ssl = isset($args[2]) && !empty($args[2]);
            if ($args[0] == $neardBins->getApache()->getName()) {
                $bin = $neardBins->getApache()->checkPort($args[1], $ssl, true);
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $bin = $neardBins->getMysql()->checkPort($args[1], true);
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $bin = $neardBins->getMariadb()->checkPort($args[1], true);
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $bin = $neardBins->getFilezilla()->checkPort($args[1], $ssl, true);
            }
        }
    }
}
