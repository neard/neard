<?php

class ActionSwitchHost
{
    const SWITCH_ON = 'on';
    const SWITCH_OFF = 'off';
    
    public function __construct($args)
    {
        Util::refactorHostsFile();
        $ip = isset($args[0]) && Util::isValidIp($args[0]) ? $args[0] : null;
        $domain = isset($args[1]) && !empty($args[1]) ? $args[1] : null;
        $switch = isset($args[2]) && !empty($args[2]) ? $args[2] : null;
        
        if (!empty($ip) && !empty($domain) && !empty($switch)) {
            $onContent = str_pad($ip, 20) . $domain;
            $offContent = '# ' . str_pad($ip, 18) . $domain;
            
            $hostsContent = file_get_contents(HOSTS_FILE);
            if ($switch == self::SWITCH_ON) {
                $hostsContent = str_replace($offContent, $onContent, $hostsContent);
            } elseif ($switch == self::SWITCH_OFF) {
                $hostsContent = str_replace($onContent, $offContent, $hostsContent);
            }
            
            file_put_contents(HOSTS_FILE, $hostsContent);
            Util::refactorHostsFile();
        }
    }

}
