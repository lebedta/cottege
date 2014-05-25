<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

?>
<?php if ($this->params->get('CssCompress') == '0') {
    $cookieStyle = (isset($_COOKIE['dj_real_estate02'])) ? $_COOKIE['dj_real_estate02'] : ''; 
?>
	<link href="<?php echo $jm_path;?>css/compressed_css.php?direction=<?php echo $direction; ?>&amp;style=<?php echo $templatestyle; ?>&amp;cookiestyle=<?php echo $cookieStyle; ?>&amp;styleswitcher=<?php echo $styleswitcher; ?>" rel="stylesheet" type="text/css" />
<?php } else { ?>
	<link href="<?php echo $jm_path;?>css/editor.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $jm_path;?>css/layout.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $jm_path;?>css/modules.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $jm_path;?>css/template.css" rel="stylesheet" type="text/css" />
<?php if($direction == 'rtl') { ?>
    <?php if($styleswitcher) { ?>
        <link href="<?php echo $jm_path; ?>css/style<?php echo (isset($_COOKIE['dj_real_estate02']) ? $_COOKIE['dj_real_estate02'] : $templatestyle); ?>_rtl.css" rel="stylesheet" media="all" type="text/css" />
        <?php } else {  ?>
        <link href="<?php echo $jm_path; ?>css/style<?php echo $templatestyle; ?>_rtl.css" rel="stylesheet" media="all" type="text/css" />
        <?php } ?>
    <?php } else  { ?>
    <?php if($styleswitcher) { ?>
        <link href="<?php echo $jm_path; ?>css/style<?php echo (isset($_COOKIE['dj_real_estate02']) ? $_COOKIE['dj_real_estate02'] : $templatestyle); ?>.css" rel="stylesheet" media="all" type="text/css" />
        <?php } else {  ?>
        <link href="<?php echo $jm_path; ?>css/style<?php echo $templatestyle; ?>.css" rel="stylesheet" media="all" type="text/css" />
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php if($direction == 'rtl') { ?>
	<link href="<?php echo $jm_path;?>css/rtl.css" rel="stylesheet" type="text/css" />
<?php } ?>
<!--[if IE 7]>
    <link href="<?php echo $jm_path;?>css/ie7.css" rel="stylesheet" type="text/css" />
    <?php if($direction == 'rtl'): ?>
	<link href="<?php echo $jm_path;?>css/ie7_rtl.css" rel="stylesheet" type="text/css" />
	<?php endif; ?>
<![endif]-->
<!--[if lte IE 8]>
<link href="<?php echo $jm_path;?>css/ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 9]>
<link href="<?php echo $jm_path;?>css/ie9.css" rel="stylesheet" type="text/css" />
<?php if($direction == 'rtl'): ?>
	<link href="<?php echo $jm_path;?>css/ie9_rtl.css" rel="stylesheet" type="text/css" />
	<?php endif; ?>
<![endif]-->
<?php if ($faviconimg == "") { ?>
<link href="<?php echo $jm_path;?>images/favicon.ico" rel="shortcut icon" />
<?php } else { ?>
<link href="<?php echo JURI::base().$this->params->get('FavIconImg'); ?>" rel="shortcut icon" />
<?php } ?>
<?php if ($bodyfontchooser == "2") { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $bodygooglewebfont; ?>" />
<?php } ?>
<?php if ($headingsfontchooser == "2") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $headingsgooglewebfont; ?>" />
<?php } ?>
<?php if ($articlesfontchooser == "2") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $articlesgooglewebfont; ?>" />
<?php } ?>
<?php if ($djmenufontchooser == "2") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $djmenugooglewebfont; ?>" />
<?php } ?>
<?php if ($advancedfontchooser == "2") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $advancedgooglewebfont; ?>" />
<?php } ?>
<?php if (($googlewebmaster == '0') && $this->params->get('GoogleWebmasterMeta') !='') : ?>
	<meta name="google-site-verification" content="<?php echo $googlewebmastermeta; ?>" />
<?php endif; ?>