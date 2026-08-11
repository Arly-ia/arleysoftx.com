<?php
$apiKey = "d2bf88d0a03ec1f1d916fa18124d6e26";
$sports = [
    'soccer_epl',
    'soccer_spain_la_liga',
    'soccer_spain_segunda_division',
    'soccer_italy_serie_a',
    'soccer_italy_serie_b',
    'soccer_germany_bundesliga',
    'soccer_germany_bundesliga2',
    'soccer_france_ligue_one',
    'soccer_france_ligue_two',
    'soccer_usa_mls',
    'soccer_mexico_ligamx',
    'soccer_brazil_campeonato',
    'soccer_argentina_primera_division',
    'soccer_netherlands_eredivisie',
    'soccer_portugal_primeira_liga',
    'soccer_belgium_first_div',
    'soccer_turkey_super_league',
    'soccer_uefa_champs_league',
    'soccer_uefa_europa_league',
    'soccer_conmebol_copa_libertadores',
    'baseball_mlb',
    'baseball_ncaa',
    'basketball_nba',
    'basketball_wnba',
    'basketball_euroleague',
    'americanfootball_nfl',
    'americanfootball_ncaaf'
];

$totalCount = 0;
foreach ($sports as $sKey) {
    $url = "https://api.the-odds-api.com/v4/sports/{$sKey}/odds/?regions=us,uk,eu,au&markets=h2h&oddsFormat=decimal&apiKey=" . $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $d = json_decode($res, true);
    $count = is_array($d) ? count($d) : 0;
    $totalCount += $count;
    if ($count > 0) {
        echo "[$sKey] -> $count matches" . PHP_EOL;
    }
}
echo "TOTAL REAL MATCHES IN ALL LEAGUES: $totalCount" . PHP_EOL;
