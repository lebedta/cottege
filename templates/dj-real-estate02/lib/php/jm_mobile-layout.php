<div id="jm-main" class="ismobile">
    <div id="jm-main-in" class="clearfix">
        <div id="jm-content-right">
            <?php if ($this->countModules('breadcrumbs') || ($fontswitcher)) : ?>
            <div id="jm-pathway-font-switcher" class="clearfix <?php echo $nofontsw; ?>">
                <?php if ($this->countModules('breadcrumbs')) : ?>
                <div id="jm-pathway">
                    <jdoc:include type="modules" name="breadcrumbs" style="xhtml"/>
                </div>
                <?php endif; ?>
                <?php if($fontswitcher) : ?>
                <div id="jm-font-switcher">
                    <a href="index.php" class="texttoggler" rel="smallview" title="small size"><img src="<?php echo $jm_path; ?>images/smaller.png" alt="Smaller" /></a>
                    <a href="index.php" class="texttoggler" rel="normalview" title="normal size"><img src="<?php echo $jm_path; ?>images/default.png" alt="Default" /></a>
                    <a href="index.php" class="texttoggler" rel="largeview" title="large size"><img src="<?php echo $jm_path; ?>images/larger.png" alt="Larger" /></a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div id="jm-maincontent">
	            <jdoc:include type="message" />
	            <jdoc:include type="component" />
            </div>
            <div id="jm-left">
                <div class="jm-left-in">
                    <jdoc:include type="modules" name="left-column" style="jmmodule"/>
                    <jdoc:include type="modules" name="right-column" style="jmmodule"/>
                </div>
            </div>
            <?php if ($this->countModules('content-top1')) : ?>
            <div id="jm-content-top1">
                <?php echo DJModuleHelper::renderModules('content-top1','jmmodule',2); ?>
            </div>
            <?php endif; ?>
            <?php if ($this->countModules('content-top2')) : ?>
            <div id="jm-content-top2" class="clearfix">
                <?php echo DJModuleHelper::renderModules('content-top2','jmmodule',1); ?>
            </div>
            <?php endif; ?>
            <?php if ($this->countModules('content-bottom1')) : ?>
            <div id="jm-content-bottom1">
                <?php echo DJModuleHelper::renderModules('content-bottom1','jmmodule',1); ?>
            </div>
            <?php endif; ?>
            <?php if ($this->countModules('content-bottom2')) : ?>
            <div id="jm-content-bottom2" class="clearfix">
                <?php echo DJModuleHelper::renderModules('content-bottom2','jmmodule',2); ?>
            </div>
            <?php endif; ?>
            <?php if ($this->countModules('top')) : ?>
            <div id="jm-top">
                <?php echo DJModuleHelper::renderModules('top','jmmodule',4); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>