<style type="text/css">
  #phpinfo {font-size: 110%; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; padding-top: 20px;}
  #phpinfo .label {font-size: 90%;}
  #phpinfo pre {margin: 0; font-family: monospace;}
  #phpinfo a:link {color: #000099; text-decoration: none; background-color: #fff;}
  #phpinfo a:hover {text-decoration: underline;}
  #phpinfo table {border-collapse: collapse; width: 75%;}
  #phpinfo .center {text-align: center;}
  #phpinfo .center table {margin-left: auto; margin-right: auto; text-align: left;}
  #phpinfo th {font-weight: bold; background: #eee;}
  #phpinfo td, th {border: 1px solid #000; font-size: 85%; vertical-align: baseline; padding: 5px;}
  #phpinfo td.v {word-wrap: break-word; word-break: break-all; }
  #phpinfo h1 {font-size: 150%;}
  #phpinfo h2 {text-align:center; font-size: 125%;}
  #phpinfo h2 div {width: 75%; margin-left: auto; margin-right: auto; color: #fff; background-color: #a59ace; border-color: #7a66d2}
  #phpinfo .p {text-align: left;}
  #phpinfo .e {width: 25%; max-width: 25%; word-wrap: break-word; word-break: break-all; background-color: #ccccff; font-weight: bold; color: #000;}
  #phpinfo .h {background-color: #9999cc; font-weight: bold; color: #000;}
  #phpinfo .v {background-color: #ccc; color: #000;}
  #phpinfo .vr {background-color: #ccc; text-align: right; color: #000;}
  #phpinfo img {float: right; border: 0;}
  #phpinfo hr {width: 600px; background-color: #cccccc; border: 0; height: 1px; color: #000;}
</style>

<a href="<?php echo $neardHomepage->getPageQuery(Homepage::PAGE_INDEX); ?>" class="btn btn-primary" role="button"><i class="fa fa-arrow-circle-left"></i> <?php echo $neardLang->getValue(Lang::HOMEPAGE_BACK_TEXT); ?></a>

<div id="phpinfo"><?php

ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();

$phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
$phpinfo = str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', $phpinfo);
$phpinfo = str_replace('<br />', '', $phpinfo);
$phpinfo = preg_replace('#<h2><a name="module_.*">(.*)</a></h2>#i', '<h2><div class="alert alert-info">$1</div></h2>', $phpinfo);
$phpinfo = preg_replace('#(th|"v")>(on|enabled|active)#i', '$1><span class="label label-success">$2</span>', $phpinfo);
$phpinfo = preg_replace('#(th|"v")>(off|disabled)#i', '$1><span class="label label-danger">$2</span>', $phpinfo);
echo $phpinfo;

?></div>
