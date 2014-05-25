<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;
// including base setup file
include_once (JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_mobile-detect.php');
include_once (JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_setup.php');
?>
<?php if ($this->direction == 'rtl') { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <?php } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <?php } ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $direction; ?>" >
<head>
    <jdoc:include type="head" />
    <?php
    // including template header files
    include_once (JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_head.php');
    ?>
    <?php

    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');

    // cache folder - templates/template/css/cache/
    if (JFolder::exists(JPATH_ROOT.DS."cache".DS.'tpl-'.$this->template) == false) {
        JFolder::create(JPATH_ROOT.DS."cache".DS.'tpl-'.$this->template);
    }

    // unique hash
    $tplParamsHash = md5($this->params->toString());

    /* CUSTOM CSS */

    // file name
    $customCss = 'style_custom_'.$tplParamsHash.'.css';
    // CSS path
    $customCssPath = JPATH_ROOT.DS.'cache'.DS.'tpl-'.$this->template.DS.$customCss;
    // CSS URL
    $customCssURL = JURI::base().'cache/tpl-'.$this->template.'/'.$customCss;
    // CSS source
    $customCssSrc = JPATH_ROOT.DS.'templates'.DS.$this->template.DS.'css'.DS.'style_custom_css.php';

    if (!JFile::exists($customCssPath) && JFile::exists($customCssSrc)) {
        // if there's nothing in cache, let's cache the css.
        ob_start();
        // PHP file which uses template parameters to generate CSS content
        include($customCssSrc);

        $cssContent = ob_get_contents();
        ob_end_clean();
        if ($cssContent) {
            JFile::write($customCssPath, $cssContent);
        }
    }

    // if CSS exists append it to the header
    if (JFile::exists($customCssPath)) {
        echo '<link href="'.$customCssURL.'" rel="stylesheet" type="text/css" />';
    }

    /* RESPONSIVE CSS */

    // file name
    $responsiveCss = 'responsive_'.$tplParamsHash.'.css';
    // CSS path
    $responsiveCssPath = JPATH_ROOT.DS."cache".DS.'tpl-'.$this->template.DS.$responsiveCss;
    // CSS URL
    $responsiveCssURL = JURI::base().'cache/tpl-'.$this->template.'/'.$responsiveCss;
    // CSS source
    $responsiveCssSrc = JPATH_ROOT.DS."templates".DS.$this->template.DS.'css'.DS.'responsive_css.php';

    if (!JFile::exists($responsiveCssPath) && JFile::exists($responsiveCssSrc)) {
        // if there's nothing in cache, let's cache the css.
        ob_start();

        // PHP file which uses template parameters to generate CSS content
        include($responsiveCssSrc);

        $cssContent = ob_get_contents();
        ob_end_clean();
        if ($cssContent) {
            JFile::write($responsiveCssPath, $cssContent);
        }
    }

    // if CSS exists append it to the header
    if (JFile::exists($responsiveCssPath)) {
        echo '<link href="'.$responsiveCssURL.'" rel="stylesheet" type="text/css" />';
    }
    ?>
    <!-- viewport fix for some devices -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0" />
</head>
<body class="<?php echo $notopbar; echo $noheaderblock; ?>">
<div id="jm-allpage">
    <?php if ($this->countModules('top-bar1') || $this->countModules('top-bar2') || $this->countModules('top-bar3')) : ?>
    <div id="jm-top-bar" <?php echo $noheader; ?>>
        <div id="jm-top-bar-in" class="clearfix">
            <?php if (($this->countModules('top-bar1'))) : ?>
            <div id="jm-top-bar1">
                <jdoc:include type="modules" name="top-bar1" style="raw" />
            </div>
            <?php endif; ?>
            <?php if (($this->countModules('top-bar3'))) : ?>
            <div id="jm-top-bar3">
                <jdoc:include type="modules" name="top-bar3" style="raw" />
            </div>
            <?php endif; ?>
            <?php if (($this->countModules('top-bar2'))) : ?>
            <div id="jm-top-bar2">
                <jdoc:include type="modules" name="top-bar2" style="raw" />
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if (($logo != null) || ($sitetitle != null) || ($sitedescription != null) || $this->countModules('header')) : ?>
    <div id="jm-header">
        <?php if (($logo != null) || ($sitetitle != null)) : ?>
            <div id="jm-header-in" <?php echo $noheader; ?>>
                <div id="jm-logo-sitedesc">
                    <h1 id="jm-logo"><a href="<?php echo JURI::base(); ?>" onfocus="blur()" ><?php if ($logo != null ) : ?><img src="<?php echo JURI::base(); ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($sitetitle);?>" border="0" /><?php else : ?><?php echo htmlspecialchars($sitetitle);?><?php endif; ?></a></h1>
                    <?php if ($sitedescription != null) : ?>
                    <div id="jm-sitedesc">
                        <?php echo htmlspecialchars($sitedescription); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->countModules('header')) : ?>
        <div id="jm-header-bg">
            <jdoc:include type="modules" name="header" style="raw" />
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div id="jm-midpage">
        <div id="jm-midpage-in" <?php echo $nodjmenutop; ?>>
            <div id="jm-midpage-space" class="clearfix">
                <?php if($this->countModules('dj-menu-top')) : ?>
                <div id="jm-djmenu" class="clearfix">
                    <jdoc:include type="modules" name="dj-menu-top" style="raw"/>
                </div>
                <?php endif; ?>
                <?php
                // including template layouts for desktop, mobile and tablet devices
                $detect = new JMMobile_Detect();
                $isMobile = $detect->isMobile();
                $isTablet = $detect->isTablet();
                if ($isMobile && !$isTablet) {
                    include_once (JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_mobile-layout.php');
                }
                else {
                    include_once (JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_desktop-layout.php');
                }
                ?>
                <?php if ($this->countModules('bottom1') || $this->countModules('bottom2') )   : ?>
                <div id="jm-bottompage">
                    <?php if ($this->countModules('bottom1')) : ?>
                    <div id="jm-bottom1">
                        <?php echo DJModuleHelper::renderModules('bottom1','jmmodule',4); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->countModules('bottom2')) : ?>
                    <div id="jm-bottom2">
                        <jdoc:include type="modules" name="bottom2" style="jmmodule" />
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div id="jm-footer">
            <div id="jm-footer-in" class="clearfix">
                <?php if ($this->countModules('bottom-menu')) : ?>
                <div id="jm-bottom-menu">
                    <jdoc:include type="modules" name="bottom-menu" style="raw" />
                </div>
                <?php endif; ?>
                <?php if($this->countModules('copyrights')) : ?>
                <div id="jm-copyrights">
                    <jdoc:include type="modules" name="copyrights" style="raw" />
                </div>
                <?php endif; ?>
                <div id="jm-poweredby-stylearea" style="display:none;">
                    <div id="jm-poweredby">
                        <a href="http://www.joomla-monster.com/" onfocus="blur()" target="_blank" title="Joomla Templates">Joomla Templates</a> by Joomla-Monster.com
                    </div>
                    <?php if($styleswitcher) : ?>
                    <div id="jm-stylearea">
                        <a href="#" id="style_icon-1" class="style_switcher"><span>&nbsp;</span></a>
                        <a href="#" id="style_icon-2" class="style_switcher"><span>&nbsp;</span></a>
                        <a href="#" id="style_icon-3" class="style_switcher"><span>&nbsp;</span></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($backtotop) : ?> 
	<p id="jm-back-top" style="display: block;"><a href="#top"><span></span>&nbsp;</a></p>
	<?php endif; ?>
</div>
<jdoc:include type="modules" name="debug" style="raw"/>
<?php
// including javascript files
include_once(JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_javascript.php');
include_once(JPATH_ROOT."/templates/".$this->template.'/lib/php/jm_google-analytics.php');
?>
</body>
</html>
