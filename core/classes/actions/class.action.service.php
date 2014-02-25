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
        global $neardBs, $neardBins;
        
        // reload bins
        $neardBins->reload();
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $sName = $args[0];
            $name = '';
            $service = '';
            $port = 0;
            
            if ($sName == BinApache::SERVICE_NAME) {
                $name = $neardBins->getApache()->getName();
                $service = $neardBins->getApache()->getService();
                $port = $neardBins->getApache()->getPort();
            } elseif ($sName == BinMysql::SERVICE_NAME) {
                $name = $neardBins->getMysql()->getName();
                $service = $neardBins->getMysql()->getService();
                $port = $neardBins->getMysql()->getPort();
            } elseif ($sName == BinMariadb::SERVICE_NAME) {
                $name = $neardBins->getMariadb()->getName();
                $service = $neardBins->getMariadb()->getService();
                $port = $neardBins->getMariadb()->getPort();
            }
            
            if (!empty($port) && !empty($service) && $service instanceof Win32Service) {
                if ($args[1] == self::CREATE) {
                    $this->create($service);
                } elseif ($args[1] == self::DELETE) {
                    $this->delete($service);
                } elseif ($args[1] == self::START) {
                    $this->start($service);
                } elseif ($args[1] == self::STOP) {
                    $this->stop($service);
                } elseif ($args[1] == self::RESTART) {
                    $this->restart($service);
                } elseif ($args[1] == self::INSTALL) {
                    $this->install($service, $port, $name);
                } elseif ($args[1] == self::REMOVE) {
                    $this->remove($service, $name);
                }
            }
        }
    }
    
    private function create($service)
    {
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        $service->create();
    }
    
    private function delete($service)
    {
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        $service->delete();
    }
    
    private function start($service)
    {
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        $service->start();
    }
    
    private function stop($service)
    {
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        $service->stop();
    }
    
    private function restart($service)
    {
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        $service->restart();
    }
    
    private function install($service, $port, $name)
    {
        global $neardBs, $neardLang, $neardBins, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::INSTALL_SERVICE_TITLE), $name);
        
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        
        if (!Util::isPortInUse($port)) {
            if (!$service->isInstalled()) {
                $service->create();
                if ($service->start() !== true) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::SERVICE_INSTALLED), $name, $service->getName(), $port),
                        $boxTitle);
                } else {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::SERVICE_INSTALL_ERROR), $name),
                        $boxTitle);
                }
            } else {
                $neardWinbinder->messageBoxWarning(
                    sprintf($neardLang->getValue(Lang::SERVICE_ALREADY_INSTALLED), $name),
                    $boxTitle);
            }
        } else if($neardBins->getApache()->getService()->isRunning()) {
            $neardWinbinder->messageBoxWarning(
                sprintf($neardLang->getValue(Lang::SERVICE_ALREADY_INSTALLED), $name),
                $boxTitle);
        } else {
            $neardWinbinder->messageBoxError(
                sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port),
                $boxTitle);
        }
    }
    
    private function remove($service, $name)
    {
        global $neardBs, $neardLang, $neardBins, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::REMOVE_SERVICE_TITLE), $name);
    
        if (!($service instanceof Win32Service)) {
            Util::logError('$service not an instance of Win32Service');
            return;
        }
        
        if ($service->isInstalled()) {
            if ($service->delete()) {
                $neardWinbinder->messageBoxInfo(
                    sprintf($neardLang->getValue(Lang::SERVICE_REMOVED), $name),
                    $boxTitle);
            } else {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::SERVICE_REMOVE_ERROR), $name),
                    $boxTitle);
            }
        } else {
            $neardWinbinder->messageBoxWarning(
                sprintf($neardLang->getValue(Lang::SERVICE_NOT_EXIST), $name),
                $boxTitle);
        }
    }
    
}
