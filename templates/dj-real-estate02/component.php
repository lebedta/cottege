<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die;

if(JRequest::getVar('direction')=='rtl'){
	setcookie("djdirection", "rtl");
	$direction='rtl';
} else if(JRequest::getVar('direction')=='ltr'){
	setcookie("djdirection", "ltr");
	$direction='ltr';
} else {
	if(isset($_COOKIE['djdirection'])){
		$direction=$_COOKIE['djdirection'];
	}else{
		$direction=JRequest::getVar('djdirection',$this->direction);	
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $direction; ?>">
<head>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>/templates/system/css/system.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>/templates/dj-real-estate02/css/template.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>/templates/dj-real-estate02/css/print.css" type="text/css"/>
<?php if($direction == 'rtl'): ?>
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>/templates/dj-real-estate02/css/rtl.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>/templates/dj-real-estate02/css/print_rtl.css" type="text/css" />
<?php endif; ?>
</head>
<body class="contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>