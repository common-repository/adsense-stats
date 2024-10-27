<?PHP
/*
  Version: 0.1
  Author: Eduardo Sada
  Author URI: http://www.coders.me/
*/
  function parse_adsense_report($handle)
  {
    if ($handle)
    {
      $delim = chr(9);
      $header = fgetcsv($handle, 0, $delim);
      while (($val = fgetcsv($handle, 0, $delim)) !== FALSE)
      {
        $val = array($val[0], str_replace(".","", $val[1]), str_replace(".","", $val[2]), str_replace("\"","", $val[3]), str_replace(",",".", str_replace("\"","", $val[5])));
        if (strtotime($val[0]))
        {
          $adsense[$val[0]] = $val;
        }
      }
      fclose($handle);
      return $adsense;
    }
  }
?>