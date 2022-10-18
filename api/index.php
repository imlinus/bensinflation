<?php
// https://tanka.se/prishistorik
$startOfYear = array(
  '95' => 17.69,
  'diesel' => 18.92,
  'e85' => 17.18
);

// keep a simple cache to reduce requests on external api
$cacheFile = "./prices.json";
$cacheResult = json_decode(file_get_contents($cacheFile), true);

// see when the latest cache was made
if ($cacheResult['date'] == date('Y-m-d')) {
  // display cached result
  $current = array(
    '95' => $cacheResult['95'],
    'diesel' => $cacheResult['diesel'],
    'e85' => $cacheResult['e85']
  );
} else {
  // otherwise get todays prices from tanka.se
  function fetchCurrentPrice () {
    $url = "https://tanka.se/api/prices/single";
    $fetch = file_get_contents($url);
    $result = json_decode($fetch, true);

    return array(
      'date' => $result['date'],
      '95' => $result['95'],
      'diesel' => $result['diesel'],
      'e85' => $result['e85']
    );
  }

  $result = fetchCurrentPrice();
  file_put_contents($cachFile, json_encode($result));

  $current = array(
    '95' => $result['95'],
    'diesel' => $result['diesel'],
    'e85' => $result['e85']
  );
}

// ((B – A) / A) x 100 = Inflation Rate 
$gasoline = (($current['95'] - $startOfYear['95']) / $startOfYear['95']) * 100;
$diesel = (($current['diesel'] - $startOfYear['diesel']) / $startOfYear['diesel']) * 100;
$ethanol = (($current['e85'] - $startOfYear['e85']) / $startOfYear['e85']) * 100;

// create output array
$output = array(
    'startOfYear' => $startOfYear,
    'current' => $current,
    'percentage' => array(
        '95' => (float)sprintf("%.2f", $gasoline),
        'diesel' => (float)sprintf("%.2f", $diesel),
        'e85' => (float)sprintf("%.2f", $ethanol)
    )
);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json; charset=utf-8');
echo json_encode($output);
?>
