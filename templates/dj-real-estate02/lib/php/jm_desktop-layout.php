<div id="jm-main" class="<?php echo $currentScheme;?>">
    <div id="jm-main-in" class="clearfix">
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

        <?php if ($this->countModules('top')) : ?>
        <div id="jm-top">
            <?php echo DJModuleHelper::renderModules('top','jmmodule',4); ?>
        </div>
        <?php endif; ?>

        <?php
        if (!is_array($schemeOutput)) {
            echo '<p align="center"><b>Wrong SCHEME OPTION. Please, set valid scheme name<b></p>';
        } else {
            $i = 1;
            foreach ($schemeOutput as $item) {
                if (stristr($item, 'left')) {
                    $leftClassName = '';
                    if ($i == 1) {
                        $leftClassName = 'first';
                    } else if (in_array('content', $schemeOutput)) {
                        $leftClassName = 'second';
                    } else {
                        $leftClassName = 'third';
                    }
                    ?>
                    <div id="jm-left" class="<?php echo $leftClassName; ?>">
                        <div class="jm-left-in clearfix">
                            <jdoc:include type="modules" name="left-column" style="jmmodule"/>
                        </div>
                    </div>
                    <?php
                } else if (stristr($item, 'content_right')) {
                    ?>
                    <div id="jm-content-right">
                        <?php if ($this->countModules('content-top1')) : ?>
                        <div id="jm-content-top1">
                            <?php echo DJModuleHelper::renderModules('content-top1','jmmodule',2); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->countModules('content-top2')) : ?>
                        <div id="jm-content-top2">
                            <?php echo DJModuleHelper::renderModules('content-top2','jmmodule',1); ?>
                        </div>
                        <?php endif; ?>
                        <div id="jm-content" class="<?php echo ($i == 1) ? 'first' : 'second'; ?>">
                            <div class="jm-content-in">
                                <div id="jm-maincontent">
                                    <jdoc:include type="message" />
                                    <jdoc:include type="component" />
                                </div>
                                <?php if ($this->countModules('content-bottom1')) : ?>
                                <div id="jm-content-bottom1">
                                    <?php echo DJModuleHelper::renderModules('content-bottom1','jmmodule',1); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($this->countModules('content-bottom2')) : ?>
                                <div id="jm-content-bottom2">
                                    <?php echo DJModuleHelper::renderModules('content-bottom2','jmmodule',2); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="jm-right" class="<?php echo ($i == 1) ? 'second' : 'third'; ?>">
                            <div class="jm-right-in">
                                <jdoc:include type="modules" name="right-column" style="jmmodule"/>
                            </div>
                        </div>
                    </div>
                    <?php
                } else if (stristr($item, 'right_content')) {
                    ?>
                    <div id="jm-content-right">
                        <?php if ($this->countModules('content-top1')) : ?>
                        <div id="jm-content-top1">
                            <?php echo DJModuleHelper::renderModules('content-top1','jmmodule',2); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->countModules('content-top2')) : ?>
                        <div id="jm-content-top2">
                            <?php echo DJModuleHelper::renderModules('content-top2','jmmodule',1); ?>
                        </div>
                        <?php endif; ?>
                        <div id="jm-right" class="<?php echo ($i == 1) ? 'first' : 'second'; ?>">
                            <div class="jm-right-in">
                                <jdoc:include type="modules" name="right-column" style="jmmodule"/>
                            </div>
                        </div>
                        <div id="jm-content" class="<?php echo ($i == 1) ? 'second' : 'third';?>">
                            <div class="jm-content-in">
                                <div id="jm-maincontent">
                                    <jdoc:include type="message" />
                                    <jdoc:include type="component" />
                                </div>
                                <?php if ($this->countModules('content-bottom1')) : ?>
                                <div id="jm-content-bottom1">
                                    <?php echo DJModuleHelper::renderModules('content-bottom1','jmmodule',1); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($this->countModules('content-bottom2')) : ?>
                                <div id="jm-content-bottom2">
                                    <?php echo DJModuleHelper::renderModules('content-bottom2','jmmodule',2); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                } else if (stristr($item, 'content')) {
                    ?>
                    <div id="jm-content-right">
                        <?php if ($this->countModules('content-top1')) : ?>
                        <div id="jm-content-top1">
                            <?php echo DJModuleHelper::renderModules('content-top1','jmmodule',2); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($this->countModules('content-top2')) : ?>
                        <div id="jm-content-top2">
                            <?php echo DJModuleHelper::renderModules('content-top2','jmmodule',1); ?>
                        </div>
                        <?php endif; ?>

                        <div id="jm-content" class="<?php echo ($i == 1) ? 'first' : 'second';?>">
                            <div class="jm-content-in">
                                <div id="jm-maincontent">
                                    <jdoc:include type="message" />
                                    <jdoc:include type="component" />
                                </div>
                                <?php if ($this->countModules('content-bottom1')) : ?>
                                <div id="jm-content-bottom1">
                                    <?php echo DJModuleHelper::renderModules('content-bottom1','jmmodule',1); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($this->countModules('content-bottom2')) : ?>
                                <div id="jm-content-bottom2">
                                    <?php echo DJModuleHelper::renderModules('content-bottom2','jmmodule',2); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                $i++;
            }
        }
        ?>
    </div>
</div>