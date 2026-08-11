<?php
$apiKey = "eda6419db414258c030ca26210dc43bf";
$today = date('Y-m-d');

function queryApiSports($host, $endpoint, $apiKey) {
    $url = "https://{$host}/{$endpoint}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["x-apisports-key: {$apiKey}"]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, json_decode($res, true)];
}

// 4. Test Basketball
echo "=== TESTING BASKETBALL ===" . PHP_EOL;
list($c, $d) = queryApiSports("v1.basketball.api-sports.io", "games?date={$today}", $apiKey);
$response = $d['response'] ?? [];
echo "Basketball games count: " . count($response) . PHP_EOL;
foreach (array_slice($response, 0, 5) as $g) {
    echo "  " . ($g['teams']['home']['name'] ?? '') . " vs " . ($g['teams']['away']['name'] ?? '') . " | League: " . ($g['league']['name'] ?? '') . PHP_EOL;
}
