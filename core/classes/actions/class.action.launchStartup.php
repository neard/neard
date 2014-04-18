<?php

class ActionLaunchStartup
{
    public function __construct($args)
    {
        global $neardConfig, $neardRegistry;
        
        if (isset($args[0]) && !empty($args[0])) {
            Util::startLoading();
            $launchStartup = $args[0] == Config::LAUNCH_STARTUP_ON;
            if ($launchStartup) {
                Util::setLaunchStartupRegKey();
            } else {
                Util::deleteLaunchStartupRegKey();
            }
            $neardConfig->replace(Config::CFG_LAUNCH_STARTUP, $launchStartup ? Config::LAUNCH_STARTUP_ON : Config::LAUNCH_STARTUP_OFF);
        }
    }

}
