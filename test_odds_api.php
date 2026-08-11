<?php
$apiKey = "d2bf88d0a03ec1f1d916fa18124d6e26";

$sportKeys = [
    'upcoming' => 'upcoming',
    'Premier League' => 'soccer_epl',
    'La Liga' => 'soccer_spain_la_liga',
    'Champions League' => 'soccer_uefa_champs_league',
    'Serie A' => 'soccer_italy_serie_a',
    'Bundesliga' => 'soccer_germany_bundesliga',
    'Ligue 1' => 'soccer_france_ligue_one',
    'MLS' => 'soccer_usa_mls',
    'Liga MX' => 'soccer_mexico_ligamx',
    'Brazil Serie A' => 'soccer_brazil_campeonato',
    'MLB Béisbol' => 'baseball_mlb',
    'WNBA Baloncesto' => 'basketball_wnba',
    'NBA Baloncesto' => 'basketball_nba',
    'NFL Fútbol Americano' => 'americanfootball_nfl'
];

foreach ($sportKeys as $title => $key) {
    $url = "https://api.the-odds-api.com/v4/sports/{$key}/odds/?regions=uk,eu,us&markets=h2h&oddsFormat=decimal&apiKey=" . $apiKey;
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
    echo "=== $title ($key) === [Code $code]: $count matches" . PHP_EOL;
    if ($count > 0) {
        foreach (array_slice($d, 0, 3) as $ev) {
            echo "   " . $ev['home_team'] . " vs " . $ev['away_team'] . " (" . $ev['commence_time'] . ")" . PHP_EOL;
        }
    }
}
