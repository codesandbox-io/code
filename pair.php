<?php
// Twój klucz API i sekret API
$apiKey = 'YOUR_API_KEY';
$apiSecret = 'YOUR_API_SECRET';

// Funkcja do wykonania żądania GET do API 3Commas
function sendGetRequest($endpoint, $params = [])
{
    global $apiKey, $apiSecret;
    
    $url = 'https://api.3commas.io/public/api/ver1/' . $endpoint;
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'APIKEY: ' . $apiKey,
        'Signature: ' . hash_hmac('sha256', $endpoint . '?' . http_build_query($params), $apiSecret)
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Pobierz pary tokenów dla rynków spot
$spotMarkets = sendGetRequest('accounts/markets', ['market_code' => 'binance', 'market_type' => 'spot']);

// Pobierz pary tokenów dla rynków futures
$futuresMarkets = sendGetRequest('accounts/markets', ['market_code' => 'binance', 'market_type' => 'futures']);

echo "<h2>Pary Tokenów na Rynku Spot:</h2>";
echo "<ul>";
foreach ($spotMarkets as $market) {
    echo "<li>{$market['symbol']}</li>";
}
echo "</ul>";

echo "<h2>Pary Tokenów na Rynku Futures:</h2>";
echo "<ul>";
foreach ($futuresMarkets as $market) {
    echo "<li>{$market['symbol']}</li>";
}
echo "</ul>";
?>
