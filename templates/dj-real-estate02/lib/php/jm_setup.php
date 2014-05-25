<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

// setting variable for the direction
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

$this->params->set('direction', $direction);

// loading joomla core features
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');
$mosConfig_live_site = JURI::base();
$app = JFactory::getApplication();

// setting variable for the template base url
$jm_name = $app->getTemplate();
$jm_path = $mosConfig_live_site . '/templates/' . $jm_name . '/';

$this->params->set('jm_name', $jm_name);
$this->params->set('jm_path', $jm_path);

// getting information about template scheme option
$schemeoption = $this->params->get("SchemeOption");

$sideBarsScheme = array (
    //by default
    'default'=>      $schemeoption,
);

/*** DO NOT MODIFY THIS IF YOU DONT KNOW WHAT YOU DOING! ***/

$option = 'default';
if ($option && isset($sideBarsScheme[$option]) && trim($sideBarsScheme[$option]) && stristr($sideBarsScheme[$option],'content')!= false){
    $currentScheme = trim($sideBarsScheme[$option]);
} else {
    $currentScheme = $sideBarsScheme['default'];
}

if (!$this->countModules('left-column')) {
    $currentScheme = str_replace('left-','',$currentScheme);
    $currentScheme = str_replace('-left','',$currentScheme);
}
if (!$this->countModules('right-column')) {
    $currentScheme = str_replace('_right','',$currentScheme);
    $currentScheme = str_replace('right_','',$currentScheme);
}

$schemeOutput = '';
$currentSchemeWord = $currentScheme;
$currentScheme = explode("-",$currentScheme);
if (is_array ($currentScheme)) {
    foreach ($currentScheme as $item) {
        if ($item) $schemeOutput[] = $item;
    }
}

$currentSchemeWord = str_replace('_', '-', $currentSchemeWord);
$schemeArray = explode('-', $currentSchemeWord);

$currentScheme = "scheme_".count($schemeArray);

$this->params->set('current_scheme', $currentScheme);

// getting template parameters

// basic settings
$templateparams	= $app->getTemplate(true)->params;
$logo = $this->params->get('Logo');
$sitetitle = $this->params->get('SiteTitle');
$sitedescription = $this->params->get('SiteDescription');
$styleswitcher = ($this->params->get("StyleSwitcher", "0") == "1") ? false : true;
$templatestyle = $this->params->get('TemplateStyle', '1');
$fontswitcher = ($this->params->get("fontSizeSwitcher", "1") == "0") ? true : false;
$backtotop = ($this->params->get("backToTop", "0") == "1") ? false : true;
$faviconimg = $this->params->get("FavIconImg");

// template layout
$templatewidth = (int)$this->params->get("TemplateWidth", "960");
$tabletwidth = $this->params->get("TabletWidth", "800");
$mobilewidth = $this->params->get("MobileWidth", "480");
$columnleft = (int)$this->params->get("ColumnLeftWidth", "25");
$columnright = (int)$this->params->get("ColumnRightWidth", "25");
$contentwidth = 100 - $columnleft - $columnright;

$this->params->set('ContentWidth', $contentwidth);

// global styles
$fontsize = $this->params->get("fontSizeSelector", "12");
$bodyfontchooser = @current(explode(';', $this->params->get('bodyFontChooser', '0')));
$bodygooglewebfont = $this->params->get("bodyGooglewebfontChooser");
$bodygooglewebfontfamily = $this->params->get("bodyGooglewebfontFamily");
$headingsfontsize = $this->params->get("headingsFontSizeSelector", "18");
$headingsfontchooser = @current(explode(';', $this->params->get('headingsFontChooser', '0')));
$headingsgooglewebfont = $this->params->get("headingsGooglewebfontChooser");
$headingsgooglewebfontfamily = $this->params->get("headingsGooglewebfontFamily");
$articlesfontsize = $this->params->get("articlesFontSizeSelector", "18");
$articlesfontchooser = @current(explode(';', $this->params->get('articlesFontChooser', '0')));
$articlesgooglewebfont = $this->params->get("articlesGooglewebfontChooser");
$articlesgooglewebfontfamily = $this->params->get("articlesGooglewebfontFamily");
$djmenufontsize = $this->params->get("djmenuFontSizeSelector", "16");
$djmenufontchooser = @current(explode(';', $this->params->get('djmenuFontChooser', '0')));
$djmenugooglewebfont = $this->params->get("djmenuGooglewebfontChooser");
$djmenugooglewebfontfamily = $this->params->get("djmenuGooglewebfontFamily");
$advancedfontsize = $this->params->get("advancedFontSizeSelector", "18");
$advancedfontchooser = @current(explode(';', $this->params->get('advancedFontChooser', '0')));
$advancedgooglewebfont = $this->params->get("advancedGooglewebfontChooser");
$advancedgooglewebfontfamily = $this->params->get("advancedGooglewebfontFamily");
$advancedselectors = $this->params->get("advancedSelectors");

// advanced features
$googleanalytics = @current(explode(';', $this->params->get('GoogleAnalytics', '1')));
$googleanalyticscode = ($this->params->get("GoogleAnalyticsCode"));
$googlewebmaster = @current(explode(';', $this->params->get('GoogleWebmaster', '1')));
$googlewebmastermeta = ($this->params->get("GoogleWebmasterMeta"));
$csscompress = ($this->params->get("CssCompress", "1") == "0") ? true : false;

// getting information about content width depending on the number of columns
if ((!$this->countModules('left-column')) && (!$this->countModules('right-column'))) {
    $contentwidth2 = (($contentwidth + $columnright + $columnleft));
} else if (($this->countModules('left-column')) && (!$this->countModules('right-column'))) {
    $contentwidth2 = (($contentwidth + $columnright));
} else if ((!$this->countModules('left-column')) && ($this->countModules('right-column'))) {
    $contentwidth2 = (($contentwidth + $columnleft));
} else {
    $contentwidth2 = $contentwidth;
}

// getting information about content-right width
if (!$this->countModules('left-column')) {
    $two_cols = (($contentwidth + $columnright + $columnleft));
} else {
    $two_cols = (($contentwidth + $columnright));
}
$two_cols_content = round((($contentwidth2 / 100) * $templatewidth) / (($two_cols / 100) * $templatewidth) * 100);
$two_cols_right = 100 - $two_cols_content;

$this->params->set('two_cols_content', $two_cols_content);
$this->params->set('two_cols_right', $two_cols_right);


// getting information about font switcher
if 	(!$fontswitcher)  {
    $nofontsw = 'nofontsw';
} else {
    $nofontsw = '';
}

$this->params->set('nofontsw', $nofontsw);


$noheader = (!$this->countModules('header')) ? 'class="noheader"' : "";
$this->params->set('noheader', $noheader);

$nologo = (($logo == null) && ($sitetitle == null)) ? 'class="nologo"' : "";
$this->params->set('nologo', $nologo);

$nodjmenutop = (!$this->countModules('dj-menu-top')) ? 'class="nodjmenu"' : "";
$this->params->set('nodjmenu', $nodjmenutop);

$notopbar = (!$this->countModules('top-bar1') && !$this->countModules('top-bar2') && !$this->countModules('top-bar3')) ? ' notopbar' : ' topbar';
$this->params->set('notopbar', $notopbar);

$noheaderblock = (($logo == null) && ($sitetitle == null) && ($sitedescription == null) && !$this->countModules('header')) ? ' noheaderblock' : ' headerblock';
$this->params->set('noheaderblock', $noheaderblock);

// DJModuleHelper
jimport( 'joomla.application.module.helper' );
class DJModuleHelper extends JModuleHelper {
    public static function renderModules($position, $chrome = 'none', $modulesPerRow = 1) {
        if (!$position) return false;
        $html = '';
        if ($modules = parent::getModules( $position )) {
            $attribs['style'] = $chrome;
            $html .= '<div class="'.$position.' count_'.count($modules).'">';
            for ($i = 0, $k = 0; $i < count($modules); $i++) {
                $className = $position.'-in';
                // module name                                        
                //$className .= ' '.$modules[$i]->module;
                // module odd even                                   
                //$className .= ($k) ? ' even' : ' odd';
                // displaying module title                              
                //$className .= ($modules[$i]->showtitle) ? ' showtitle' : ' notitle';
                $className .= ' box'.(int)(($i % $modulesPerRow) + 1);
                if ($i % $modulesPerRow == 0) {
                    $className .= ' '.$position.'-row-first';
                    $rowClass = 'rowcount_';
                    $scheme = $modulesPerRow;
                    if ($i + $modulesPerRow > count($modules)) {
                        $scheme = count($modules) % $modulesPerRow;
                    }
                    $html .= '<div class="'.$rowClass.$scheme.' clearfix">';
                }
                if (($i+1) % $modulesPerRow == 0 || $i == count($modules)-1) {
                    $className .= ' '.$position.'-row-last';
                }
                $html .= '<div class="'.$className.'">';
                $html .= '<div class="'.$position.'-bg'.'">';
                $html .= parent::renderModule($modules[$i], $attribs);
                $html .= '</div>';
                $html .= '</div>';
                if (($i+1) % $modulesPerRow == 0 || $i == count($modules)-1) {
                    $html .= '</div>';
                }
                $k = 1 - $k;
            }
            $html .= '</div>';
        }
        return $html;
    }
}
?>