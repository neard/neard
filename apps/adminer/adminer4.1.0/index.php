<?php

include './config.php';

function adminer_object() {
	global $mysqlPort, $mariadbPort;

    include_once './plugins/plugin.php';

    foreach (glob('plugins/*.php') as $filename) {
        include_once './' . $filename;
    }
    
    $plugins = array(
        new AdminerLoginServers(
            array(
                '127.0.0.1:' . $mysqlPort => 'MySQL port ' . $mysqlPort,
                '127.0.0.1:' . $mariadbPort => 'MariaDB port ' . $mariadbPort
            )
        ),
    );
    
    /* It is possible to combine customization and plugins:
    class AdminerCustomization extends AdminerPlugin {
    }
    return new AdminerCustomization($plugins);
    */
    
    return new AdminerPlugin($plugins);
}

include './adminer.php';
