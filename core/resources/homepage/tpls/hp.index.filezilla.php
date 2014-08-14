<a class="anchor" name="filezilla"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/filezilla.png'); ?>" /> Filezilla <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item">
        <?php
        if ($neardBins->getFilezilla()->checkPort($neardBins->getFilezilla()->getSslPort(), true)) {
            ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-success"><?php echo sprintf($neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED), $neardBins->getFilezilla()->getSslPort()); ?> (SSL)</span><?php
        } else {
            ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-danger"><?php echo $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED); ?></span><?php
        }
        if ($neardBins->getFilezilla()->checkPort($neardBins->getFilezilla()->getPort())) {
            ?><span style="float:right;font-size:12px" class="label label-success"><?php echo sprintf($neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED), $neardBins->getFilezilla()->getPort()); ?></span><?php
        } else { 
            ?><span style="float:right;font-size:12px" class="label label-danger"><?php echo $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED); ?></span><?php
        } ?>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item">
        <?php foreach ($neardBins->getFilezilla()->getVersionList() as $version) {
            if ($version != $neardBins->getFilezilla()->getVersion()) {
                ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-default"><?php echo $version; ?></span><?php
            }
        } ?>
        <span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary"><?php echo $neardBins->getFilezilla()->getVersion(); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSION); ?>
      </span>
    </div>
  </div>
</div>