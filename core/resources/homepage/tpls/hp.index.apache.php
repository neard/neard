<a class="anchor" name="apache"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/apache.png'); ?>" /> <?php echo $neardLang->getValue(Lang::APACHE); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-4">
    <div class="list-group">
      <span class="list-group-item">
        <?php
        if ($neardBins->getApache()->checkPort($neardBins->getApache()->getPort())) {
            ?><span style="float:right;font-size:12px" class="label label-success"><?php echo sprintf($neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED), $neardBins->getApache()->getPort()); ?></span><?php
        } else {
            ?><span style="float:right;font-size:12px" class="label label-danger"><?php echo $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED); ?></span><?php
        } ?>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item">
        <?php foreach ($neardBins->getApache()->getVersionList() as $version) {
            if ($version != $neardBins->getApache()->getVersion()) {
                ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-default"><?php echo $version; ?></span><?php
            }
        } ?>
        <span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary"><?php echo $neardBins->getApache()->getVersion(); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSION); ?>
      </span>
      <span class="list-group-item">
        <span style="float:right;font-size:12px" class="label label-primary"><?php echo count($neardBins->getApache()->getModulesLoaded()) . ' / ' . count($neardBins->getApache()->getModules()); ?></span>
        <i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::MODULES); ?>
      </span>
      <span class="list-group-item">
        <span style="float:right;font-size:12px" class="label label-primary"><?php echo count($neardBins->getApache()->getAlias()); ?></span>
        <i class="fa fa-link"></i> <?php echo $neardLang->getValue(Lang::ALIASES); ?>
      </span>
      <span class="list-group-item">
        <span style="float:right;font-size:12px" class="label label-primary"><?php echo count($neardBins->getApache()->getVhosts()); ?></span>
        <i class="fa fa-globe"></i> <?php echo $neardLang->getValue(Lang::VIRTUAL_HOSTS); ?>
      </span>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::MODULES); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php foreach($neardBins->getApache()->getModulesFromConf() as $moduleName => $moduleStatus) {
    if ($moduleStatus == ActionSwitchApacheModule::SWITCH_ON) {
      ?><div class="col-lg-2" style="padding:3px;"><i class="fa fa-check-square-o"></i> <strong><?php echo $moduleName; ?></strong></div><?php
    } else {
      ?><div class="col-lg-2" style="padding:3px;"><i class="fa fa-square-o"></i> <?php echo $moduleName; ?></div><?php
    }
  } ?>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-link"></i> <?php echo $neardLang->getValue(Lang::ALIASES); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php foreach($neardBins->getApache()->getAlias() as $alias) {
    ?><div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="<?php echo $neardBs->getLocalUrl($alias); ?>"><span class="fa fa-link"></span> <?php echo $alias; ?></a></div><?php
  } ?>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-globe"></i> <?php echo $neardLang->getValue(Lang::VIRTUAL_HOSTS); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php foreach($neardBins->getApache()->getVhostsUrl() as $vhost => $enabled) {
    if ($enabled) {
      ?><div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="<?php echo 'http://' . $vhost; ?>"><span class="fa fa-check-square-o"></span> <?php echo $vhost; ?></a></div><?php
    } else {
      ?><div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="<?php echo 'http://' . $vhost; ?>"><span class="fa fa-square-o"></span> <?php echo $vhost; ?></a></div><?php
    }
  } ?>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-windows"></i> <?php echo $neardLang->getValue(Lang::WINDOWS_HOSTS); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php foreach(Util::getWindowsHosts() as $host) {
    if ($host['ip'] == '127.0.0.1') {
      if ($host['enabled']) {
        ?><div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="<?php echo 'http://' . $host['domain']; ?>"><span class="fa fa-check-square-o"></span> <?php echo $host['domain']; ?></a></div><?php
      } else {
        ?><div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="<?php echo 'http://' . $host['domain']; ?>"><span class="fa fa-square-o"></span> <?php echo $host['domain']; ?></a></div><?php
      }
    }
    
  } ?>
  </div>
</div>