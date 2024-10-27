<?php
/*
  Plugin Name: Adsense Stats
  Plugin URI: http://www.coders.me/wordpress/adsense-stats
  Description: Dashboard widget for Google Adsense stats.
  Version: 1.0
  Author: Eduardo Sada
  Author URI: http://www.coders.me/
*/

require_once('functions.php');


add_option('adsense_stats_username', "");
add_option('adsense_stats_password', "");
add_option('adsense_stats_date',     "");

function adsense_stats_add_dashboard_widgets()
{
  if ( current_user_can('manage_options') )
  {
    wp_add_dashboard_widget('adsense_stats_add_dashboard_widgets',       "Adsense Stats", 'dashboard_adsense_stats');
  }
}

function dashboard_adsense_stats()
{
  $flash = '../'.PLUGINDIR.'/adsense-stats/open-flash-chart.swf';
  $url   = urlencode('../'.PLUGINDIR.'/adsense-stats/data2.php');
	echo '
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
  codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
  width="500" height="250" id="graph-2" align="middle">

	<param name="allowScriptAccess" value="sameDomain" />
	<param name="flashvars" value="data-file='.$url.'" />
	<param name="movie" value="'.$flash.'" />
	<param name="quality" value="high" />

	<embed src="'.$flash.'"
		   quality="high"
		   bgcolor="#FFFFFF"
		   width="500"
		   height="250"
		   name="open-flash-chart"
		   align="middle"
		   flashvars="data-file='.$url.'"
		   type="application/x-shockwave-flash"
		   pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
	';
}


function adsense_stats_ShowOptions()
{

	if (isset($_POST['info_update'])) : ?>

  <div id="message" class="updated fade">
    <p><strong>
    <?php 
      update_option('adsense_stats_username', (string) $_POST["adsense_stats_username"]);
      update_option('adsense_stats_password', (string) $_POST["adsense_stats_password"]);
      update_option('adsense_stats_date', (int) $_POST["adsense_stats_date"]);
      _e('Settings saved.');
    ?>
    </strong></p>
  </div>
  <?php endif; ?>

	<div class="wrap">
    <div id="icon-plugins" class="icon32">
      <br/>
    </div>
    <h2>AdSense Stats</h2>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
      <input type="hidden" name="info_update" id="info_update" value="true" />

      <h3>AdSense Login Information</h3>

      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="adsense_stats_username">Adsense Username</label></th>
          <td><input name="adsense_stats_username" type="text" size="40" value="<?php echo get_option('adsense_stats_username') ?>"  class="regular-text"/></td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="adsense_stats_password">Adsense Password</label></th>
          <td><input name="adsense_stats_password" type="password" autocomplete="off" size="40" value="<?php echo get_option('adsense_stats_password') ?>" class="regular-text"/></td>
        </tr>
      </table>

      <h3>AdSense Report</h3>

      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="adsense_stats_date">Date Range</label></th>
          <td>
            <fieldset><legend class="screen-reader-text"><span>Date Range</span></legend>
              <label title="7 days"><input type="radio" value="7" name="adsense_stats_date"   <?php if((int)get_option('adsense_stats_date')==7) echo 'checked="checked"';?>/> 7 days</label><br/>
              <label title="15 days"><input type="radio" value="15" name="adsense_stats_date" <?php if((int)get_option('adsense_stats_date')==15) echo 'checked="checked"';?>/> 15 days</label><br/>
              <label title="31 days"><input type="radio" value="0" name="adsense_stats_date"  <?php if((int)get_option('adsense_stats_date')<=0) echo 'checked="checked"';?>/> 31 days</label><br/>
            </fieldset>
          </td>
        </tr>
      </table>

      <p class="submit">
        <input type="submit" class="button-primary" name="info_update" value="<?php _e('Update options &raquo;'); ?>" />
      </p>
    </form>
    
    <h3>Donate</h3>
    <p>Support this plugin for future updates</p>
    <style>
      a.coffee{
        background: url('<?PHP echo '../'.PLUGINDIR.'/adsense-stats/images/'; ?>buy-me-a-coffee.png') no-repeat top;
        display:block;
        height:63px;
        width:246px;
      }

      a.coffee:hover{
        background-position:bottom;
      }

      a.coffee span{
        display:none;
      }
    </style>
    
    <div>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
      <img alt="" border="0" src="https://www.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
      <a href="#" onclick="jQuery(this).parent().submit(); return false;" class="coffee"><span>Buy me a coffee</span></a>
      </form>
    </div>
    
    <h3>Credits</h3>
    <p><a href="http://www.coders.me/wordpress/adsense-statsadsense-stats">Adsense Stats Plugin for Wordpress</a>. Author: <a href="http://www.coders.me/about">Eduardo Sada</a></p>
    <p><a href="http://teethgrinder.co.uk/open-flash-chart-2/">Open Flash Chart project.</a></p>
    <p>Thanks to Alex Polski for PHP class that can retrieve data from AdSense account.</p>
  </div>
	
<?php
} //end function


function adsense_stats_menu()
{
  if (function_exists('add_options_page'))
  {
    $icon = '../'.PLUGINDIR.'/adsense-stats/images/chart_bar.png';
    add_options_page('AdSense Stats', "<img src='$icon' align='absmiddle'/> AdSense Stats", 8, __FILE__, 'adsense_stats_ShowOptions');
  }
}

function adsense_stats_clear_cache()
{
  $dirname = PLUGINDIR.'/adsense-stats/cache/';
  if (is_dir($dirname))
  {
    $dir_handle = opendir($dirname);
    while ($file = readdir($dir_handle))
    {
      if ($file != "." && $file != ".." && pathinfo($file, PATHINFO_EXTENSION) == 'php')
      {
        if (!is_dir($dirname."/".$file))
        {
          unlink($dirname."/".$file);
        }
      }
    }
  }
  else
  {
    return false;
  }
  closedir($dir_handle);
  return true;  
}


add_action('adsense_stats_clear_cache_event','adsense_stats_clear_cache');
add_action('wp_dashboard_setup', 'adsense_stats_add_dashboard_widgets');
add_action('admin_menu', 'adsense_stats_menu');
wp_schedule_single_event(time()+3600, 'adsense_stats_clear_cache_event');
?>