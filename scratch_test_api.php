<?php
function fetchTSDB($endpoint) {
    $url = "https://www.thesportsdb.com/api/v1/json/3/" . $endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, json_decode($res, true)];
}

echo "=== PREMIER LEAGUE SEASON FIXTURES ===" . PHP_EOL;
list($c, $data) = fetchTSDB("eventsseason.php?id=4328");
$events = $data['events'] ?? [];
echo "Found " . count($events) . " real Premier League fixtures!" . PHP_EOL;
foreach (array_slice($events, 0, 10) as $ev) {
    echo "  " . $ev['strEvent'] . " (" . ($ev['intHomeScore'] ?? '0') . " - " . ($ev['intAwayScore'] ?? '0') . ") | Date: " . $ev['dateEvent'] . " " . $ev['strTime'] . PHP_EOL;
}

echo "=== LALIGA SEASON FIXTURES ===" . PHP_EOL;
list($c, $data) = fetchTSDB("eventsseason.php?id=4335");
$events = $data['events'] ?? [];
echo "Found " . count($events) . " real LaLiga fixtures!" . PHP_EOL;
foreach (array_slice($events, 0, 10) as $ev) {
    echo "  " . $ev['strEvent'] . " (" . ($ev['intHomeScore'] ?? '0') . " - " . ($ev['intAwayScore'] ?? '0') . ") | Date: " . $ev['dateEvent'] . " " . $ev['strTime'] . PHP_EOL;
}
