<?php

class ActionLaunchStartup
{
    public function __construct($args)
    {
        global $neardConfig, $neardRegistry;
        
        if (isset($args[0]) && !empty($args[0])) {
            Util::startLoading();
            $launchStartup = $args[0] == Config::ENABLED;
            if ($launchStartup) {
                Util::setLaunchStartupRegKey();
            } else {
                Util::deleteLaunchStartupRegKey();
            }
            $neardConfig->replace(Config::CFG_LAUNCH_STARTUP, $launchStartup ? Config::ENABLED : Config::DISABLED);
        }
    }

}
