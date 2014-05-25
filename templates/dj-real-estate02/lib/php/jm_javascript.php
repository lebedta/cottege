<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

?>
<script language="javascript" type="text/javascript" src="<?php echo $jm_path;?>/lib/js/doctextsizer.js"></script>
<script type="text/javascript">
//documenttextsizer.setup("shared_css_class_of_toggler_controls")
documenttextsizer.setup("texttoggler")
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function(){
	// hide #jm-back-top first
	jQuery("#jm-back-top").hide();
	// fade in #jm-back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#jm-back-top').fadeIn();
			} else {
				jQuery('#jm-back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		jQuery('#jm-back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
});
</script>
<script language="javascript" type="text/javascript" src="<?php echo $jm_path;?>lib/js/set_height.js"></script>
<script type="text/javascript">
	$template_path = '<?php echo JURI::base(); ?>/templates/<?php echo $this->template?>';
</script>
<?php if($direction == 'rtl') { ?>
	<script language="javascript" type="text/javascript" src="<?php echo $jm_path;?>lib/js/template_scripts_rtl.js"></script>
<?php } else  { ?>
	<script language="javascript" type="text/javascript" src="<?php echo $jm_path;?>lib/js/template_scripts.js"></script>
<?php } ?>