<style type="text/css">
  #phpinfo {}
  #phpinfo pre {margin: 0px; font-family: monospace;}
  #phpinfo a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
  #phpinfo a:hover {text-decoration: underline;}
  #phpinfo table {border-collapse: collapse;}
  #phpinfo .center {text-align: center;}
  #phpinfo .center table {margin-left: auto; margin-right: auto; text-align: left;}
  #phpinfo .center th {text-align: center !important;}
  #phpinfo td, th {border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
  #phpinfo h1 {font-size: 150%;}
  #phpinfo h2 {font-size: 125%;}
  #phpinfo .p {text-align: left;}
  #phpinfo .e {background-color: #ccccff; font-weight: bold; color: #000000;}
  #phpinfo .h {background-color: #9999cc; font-weight: bold; color: #000000;}
  #phpinfo .v {background-color: #cccccc; color: #000000;}
  #phpinfo .vr {background-color: #cccccc; text-align: right; color: #000000;}
  #phpinfo img {float: right; border: 0px;}
  #phpinfo hr {}
</style>

<a href="<?php echo $neardHomepage->getPageUrl(Homepage::PAGE_INDEX); ?>" class="btn btn-primary" role="button"><i class="fa fa-arrow-circle-left"></i> <?php echo $neardLang->getValue(Lang::HOMEPAGE_BACK_TEXT); ?></a>

<div id="phpinfo"><?php

ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();

// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
echo str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo));

?></div>