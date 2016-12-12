<?php

class ActionService
{
    const CREATE = 'create';
    const DELETE = 'delete';
    const START = 'start';
    const STOP = 'stop';
    const RESTART = 'restart';
    
    const INSTALL = 'install';
    const REMOVE = 'remove';
    
    public function __construct($args)
    {
        global $neardBins;
        Util::startLoading();
        
        // reload bins
        $neardBins->reload();
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $sName = $args[0];
            $port = 0;
            $syntaxCheckCmd = null;
            
            if ($sName == BinMailhog::SERVICE_NAME) {
                $bin = $neardBins->getMailhog();
                $port = $bin->getSmtpPort();
            } elseif ($sName == BinMemcached::SERVICE_NAME) {
                $bin = $neardBins->getMemcached();
                $port = $bin->getPort();
            } elseif ($sName == BinApache::SERVICE_NAME) {
                $bin = $neardBins->getApache();
                $port = $bin->getPort();
                $syntaxCheckCmd = BinApache::CMD_SYNTAX_CHECK;
            } elseif ($sName == BinMysql::SERVICE_NAME) {
                $bin = $neardBins->getMysql();
                $port = $bin->getPort();
                $syntaxCheckCmd = BinMysql::CMD_SYNTAX_CHECK;
            } elseif ($sName == BinMariadb::SERVICE_NAME) {
                $bin = $neardBins->getMariadb();
                $port = $bin->getPort();
                $syntaxCheckCmd = BinMariadb::CMD_SYNTAX_CHECK;
            } elseif ($sName == BinPostgresql::SERVICE_NAME) {
                $bin = $neardBins->getPostgresql();
                $port = $bin->getPort();
            } elseif ($sName == BinFilezilla::SERVICE_NAME) {
                $bin = $neardBins->getFilezilla();
                $port = $bin->getPort();
            } elseif ($sName == BinSvn::SERVICE_NAME) {
                $bin = $neardBins->getSvn();
                $port = $bin->getPort();
            }
            
            $name = $bin->getName();
            $service = $bin->getService();
            
            if (!empty($service) && $service instanceof Win32Service) {
                if ($args[1] == self::CREATE) {
                    $this->create($service);
                } elseif ($args[1] == self::DELETE) {
                    $this->delete($service);
                } elseif ($args[1] == self::START) {
                    $this->start($bin, $syntaxCheckCmd);
                } elseif ($args[1] == self::STOP) {
                    $this->stop($service);
                } elseif ($args[1] == self::RESTART) {
                    $this->restart($bin, $syntaxCheckCmd);
                } elseif ($args[1] == self::INSTALL) {
                    if (!empty($port)) {
                        $this->install($bin, $port, $syntaxCheckCmd);
                    }
                } elseif ($args[1] == self::REMOVE) {
                    $this->remove($service, $name);
                } elseif ($args[1] == self::REMOVE) {
                    $this->remove($service, $name);
                }
            }
        }
        
        Util::stopLoading();
    }
    
    private function create($service)
    {
        $service->create();
    }
    
    private function delete($service)
    {
        $service->delete();
    }
    
    private function start($bin, $syntaxCheckCmd)
    {
        Util::startService($bin, $syntaxCheckCmd, true);
    }
    
    private function stop($service)
    {
        $service->stop();
    }
    
    private function restart($bin, $syntaxCheckCmd)
    {
        if ($bin->getService()->stop()) {
            $this->start($bin, $syntaxCheckCmd);
        }
    }
    
    private function install($bin, $port, $syntaxCheckCmd)
    {
        Util::installService($bin, $port, $syntaxCheckCmd, true);
    }
    
    private function remove($service, $name)
    {
        Util::removeService($service, $name);
    }
}
