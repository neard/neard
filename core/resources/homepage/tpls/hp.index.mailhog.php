<a class="anchor" name="mailhog"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/mailhog.png'; ?>" /> <?php echo $neardLang->getValue(Lang::MAILHOG); ?> <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item mailhog-checkport">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item mailhog-versions">
        <span class="loader" style="float:right"><img src="<?php echo $neardHomepage->getResourcesPath() . '/img/loader.gif'; ?>" /></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSIONS); ?>
      </span>
      <a class="list-group-item" target="_blank" href="<?php echo $neardBs->getLocalUrl() . ':' . $neardBins->getMailhog()->getUiPort(); ?>">
        <i class="fa fa-info-circle"></i> <?php echo $neardLang->getValue(Lang::HOMEPAGE_MAILHOG_TEXT); ?>
      </a>
    </div>
  </div>
</div>