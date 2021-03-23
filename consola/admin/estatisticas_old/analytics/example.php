<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


ini_set('display_errors', 1); 
error_reporting(E_ERROR);

require_once realpath(dirname(__FILE__).'/GoogleAnalyticsServiceHandler.php');



$client_email = '1072435002683-aqi5dneb65t2m4lh74g3709aa3m9iqlh@developer.gserviceaccount.com';
$key_file = realpath(dirname(__FILE__).'/keys.p12');

$ga_handler = new GoogleAnalyticsServiceHandler($client_email,$key_file);
$ga_handler->set_profile_id('ga:82910912');

/*$data = $ga_handler->get_analytics();

echo "Page Views -> ".$data->totalsForAllResults['ga:pageviews']; 
echo "<br>Unique visitors -> ".$data->totalsForAllResults['ga:visitors']; 
print_r($data);*/


echo '<hr>Dimensional Data<hr>';

//SET DATES INTERVAL
$ga_handler->set_analytics_start_date('2015-05-01');
$ga_handler->set_analytics_end_date('2015-05-31');

//SET DIMENSIONS
$dimensions='ga:date';
$ga_handler->set_dimensions($dimensions);
//$data2 = $ga_handler->get_monthly_analytics();
//print_r($data);

$metrics='ga:visits, ga:visitors, ga:newvisits, ga:pageviews';
$ga_handler->set_metrics($metrics);

$sort='ga:date';
$ga_handler->set_sort($sort);

$filter='ga:country==Portugal';
$ga_handler->set_filter($filter);


//RUN
$data = $ga_handler->get_analytics();

echo "Page Views -> ".$data->totalsForAllResults['ga:pageviews']; 
echo "<br>Unique visitors -> ".$data->totalsForAllResults['ga:visitors'];


echo "<br><br>Page Views -> ";
print_r($data->rows);

/*$ga_handler->set_analytics_start_date('2015-05-01');
$ga_handler->set_analytics_end_date('2015-05-31');
$ga_handler->set_dimensions("ga:country,ga:region,ga:city");
$data = $ga_handler->get_analytics();
echo "Page Views -> ".$data->totalsForAllResults['ga:pageviews']; */ 





?>
