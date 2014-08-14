<a class="anchor" name="mysql"></a>
<div class="row">
  <div class="col-lg-12">
    <h1><img src="<?php echo Util::imgToBase64($neardHomepage->getPath() . '/img/mysql.png'); ?>" /> MySQL <small></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="list-group">
      <span class="list-group-item">
        <?php
        if ($neardBins->getMysql()->checkPort($neardBins->getMysql()->getPort())) {
            ?><span style="float:right;font-size:12px" class="label label-success"><?php echo sprintf($neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED), $neardBins->getMysql()->getPort()); ?></span><?php
        } else { 
            ?><span style="float:right;font-size:12px" class="label label-danger"><?php echo $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED); ?></span><?php
        } ?>
        <i class="fa fa-bar-chart-o"></i> <?php echo $neardLang->getValue(Lang::STATUS); ?>
      </span>
      <span class="list-group-item">
        <?php foreach ($neardBins->getMysql()->getVersionList() as $version) {
            if ($version != $neardBins->getMysql()->getVersion()) {
                ?><span style="float:right;font-size:12px;margin-left:2px;" class="label label-default"><?php echo $version; ?></span><?php
            }
        } ?>
        <span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary"><?php echo $neardBins->getMysql()->getVersion(); ?></span>
        <i class="fa fa-puzzle-piece"></i> <?php echo $neardLang->getValue(Lang::VERSION); ?>
      </span>
    </div>
  </div>
</div>