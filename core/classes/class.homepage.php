<?php

class Homepage
{
    const PAGE_INDEX = 'index';
    const PAGE_PHPINFO = 'phpinfo';
    
    private $page;
    private $pageList = array(
        self::PAGE_INDEX,
        self::PAGE_PHPINFO,
    );
    
    public function __construct()
    {
        Util::logInitClass($this);
        
        $page = Util::cleanGetVar('p');
        $this->page = !empty($page) && in_array($page, $this->pageList) ? $page : self::PAGE_INDEX;
    }
    
    public function getPage()
    {
        return $this->page;
    }
    
    public function getPageUrl($page)
    {
        global $neardBs;
        
        $request = '';
        if (!empty($page) && in_array($page, $this->pageList) && $page != self::PAGE_INDEX) {
            $request = '?p=' . $page;
        }
        return $neardBs->getLocalUrl($request);
    }
    
    public function getPath()
    {
        global $neardCore;
        return $neardCore->getResourcesPath(false) . '/homepage';
    }
    
    public function getResourcesUrl()
    {
        global $neardBs, $neardConfig;
        return $neardBs->getLocalUrl(md5(APP_TITLE . $neardConfig->getAppVersion()));
    }
    
    public function getAliasFilePath()
    {
        return $this->getPath() . '/alias.conf';
    }
    
    public function refreshAliasContent()
    {
        global $neardConfig, $neardBins;
    
        $result = $neardBins->getApache()->getAliasContent(
            md5(APP_TITLE . $neardConfig->getAppVersion()),
            $this->getPath());
        
        return file_put_contents($this->getAliasFilePath(), $result) !== false;
    }

}
