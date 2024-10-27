<?php
require('../../../wp-blog-header.php');
header("HTTP/1.1 200 OK");

require_once('functions.php');

$username = get_option('adsense_stats_username');
$password = get_option('adsense_stats_password');
$date     = (int) get_option('adsense_stats_date');

if ($date<=0)
{
  $date = 31;
}

$encodestring = md5(date("Ymd").'-'.date("Ymd", strtotime("-$date days")).'-'.date("H").'-'.urlencode($username));
$filename = 'cache/'.$encodestring.'.php';

if (!file_exists($filename))
{
  require_once('adsense.php');

  $login_adsense = new AdSense();
  if ($login_adsense->connect($username, $password))
  {
    if (!$handle = fopen($filename, 'x+')) {
      die("Cannot open file ($filename)");
    }
    
    if (fwrite($handle, $login_adsense->get_custom_report(strtotime("-$date days"), time())) === FALSE) {
      die ("Cannot write to file ($filename)");
    }
    rewind($handle);
    $login_adsense->log_out();
  } else {
    die('Could not login to AdSense account.');
  }
}
else
{
  $handle = fopen($filename, "r");
}

$adsense = parse_adsense_report($handle);

// magia:

foreach ((array)$adsense as $earnings)
{
  $int_fecha = (int)date('d', strtotime($earnings[0]));
  $ad['date'][]         = ($int_fecha==1||$int_fecha==15)?date('d/M', strtotime($earnings[0])):date('d', strtotime($earnings[0]));
  //$ad['impresiones'][]  = (int)$earnings[1];
  //$ad['CTR'][]          = $earnings[3];

  $ad['clicks'][]       = array ('value'=>(float)$earnings[2], 'tip'=>'Clicks: #val#');
  $ad['ingresos'][]     = array ('value'=>(float)$earnings[4], 'tip'=>'$ #val#');

  $ad['max']['clicks'][]   = (int)$earnings[2];
  $ad['max']['ingresos'][] = (float)$earnings[4];
  $today_earnings = (float)$earnings[4];
}

$json['title']['text']  = "Google Adsense Today's Earnings: $ $today_earnings";
$json['title']['style'] = '{font-size: 14px; color: #000000; font-weight:bold;}';
$json['bg_colour']      = "#FFFFFF";

$json['x_axis']['stroke']       = 1;
$json['x_axis']['steps']        = 2;
$json['x_axis']['tick_height']  = 10;
$json['x_axis']['colour']       = "#d000d0";
$json['x_axis']['grid_colour']  = "#00ff00";
$json['x_axis']['labels']['rotate'] = 'diagonal';
$json['x_axis']['labels']['labels'] = $ad['date'];

$json['y_axis']['stroke']       = 4;
$json['y_axis']['tick_height']  = 3;
$json['y_axis']['colour']       = "#d000d0";
$json['y_axis']['grid_colour']  = "#00ff00";
$json['y_axis']['offset']       = 0;
$json['y_axis']['max']          = max($ad['max']['clicks']);
$json['y_axis']['steps']        = 10;

$next_update = abs((int)date("i", time()) - 60);
$json['x_legend']['text']       = "Next Update: $next_update mins";
$json['x_legend']['style']      = '{font-size: 10px; color: #000000; font-style:italic}';

$json['y_axis_right']['stroke']       = 4;
$json['y_axis_right']['tick_height']  = 3;
$json['y_axis_right']['colour']       = "#d000d0";
$json['y_axis_right']['grid_colour']  = "#00ff00";
$json['y_axis_right']['offset']       = 0;

$json['elements'] = array(
  array('type'=>'line', 'alpha'=>0.5, "on-show"=>array("type"=>"mid-slide","cascade"=>0,"delay"=>0), 'text'=>'Clicks', 'values'=>$ad['clicks']),
  array('type'=>'line', 'axis'=> 'right', 'alpha'=>0.5, "on-show"=>array("type"=>"mid-slide","cascade"=>0,"delay"=>0), 'colour'=> '#E01B49', 'text'=>'Earnings', 'values'=>$ad['ingresos'])
);

echo json_encode($json);
?>