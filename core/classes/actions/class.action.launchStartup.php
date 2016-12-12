<?php

class ActionLaunchStartup
{
    public function __construct($args)
    {
        global $neardConfig;
        
        if (isset($args[0])) {
            Util::startLoading();
            $launchStartup = $args[0] == Config::ENABLED;
            if ($launchStartup) {
                Util::enableLaunchStartup();
            } else {
                Util::disableLaunchStartup();
            }
            $neardConfig->replace(Config::CFG_LAUNCH_STARTUP, $args[0]);
        }
    }
}
