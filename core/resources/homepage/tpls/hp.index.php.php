<a class="anchor" name="php"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/php.png'); ?>" /> <?php echo $neardLang->getValue(Lang::PHP); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item">
        <?php foreach ($neardBins->getPhp()->getVersionList() as $version) {
            if ($version != $neardBins->getPhp()->getVersion()) {
                ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-default"><?php echo $version; ?></span><?php
            }
        } ?>
        <span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary"><?php echo $neardBins->getPhp()->getVersion(); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSIONS); ?>
      </span>
      <span class="list-group-item">
        <span style="float:right;font-size:12px" class="label label-primary"><?php echo count($neardBins->getPhp()->getExtensionsLoaded()) . ' / ' . count($neardBins->getPhp()->getExtensions()); ?></span>
        <i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::EXTENSIONS); ?>
      </span>
      <span class="list-group-item">
        <span style="float:right;font-size:12px" class="label label-primary"><?php echo $neardBins->getPhp()->getPearVersion(true); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::PEAR); ?>
      </span>
      <a class="list-group-item" href="<?php echo $neardHomepage->getPageUrl(Homepage::PAGE_PHPINFO); ?>">
        <i class="fa fa-info-circle"></i> <?php echo $neardLang->getValue(Lang::HOMEPAGE_PHPINFO_TEXT); ?>
      </a>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <h3><i class="fa fa-gear"></i> <?php echo $neardLang->getValue(Lang::EXTENSIONS); ?> <small></small></h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php foreach($neardBins->getPhp()->getExtensionsFromConf() as $extName => $extStatus) {
    if ($extStatus == ActionSwitchPhpExtension::SWITCH_ON) {
      ?><div class="col-lg-2" style="padding:3px;"><i class="fa fa-check-square-o"></i> <strong><?php echo $extName; ?></strong></div><?php
    } else {
      ?><div class="col-lg-2" style="padding:3px;"><i class="fa fa-square-o"></i> <?php echo $extName; ?></div><?php
    }
  } ?>
  </div>
</div>