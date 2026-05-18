<?php
$cities = ['Cancún', 'Vancouver', 'Tokyo', 'Buenos_Aires', 'Madrid'];
$options  = ['http' => ['user_agent' => 'TravelNowImageBot/1.0 (ramae@example.com)']];
$context  = stream_context_create($options);

foreach ($cities as $city) {
    $json = file_get_contents('https://en.wikipedia.org/w/api.php?action=query&prop=pageimages&titles='.$city.'&format=json&pithumbsize=800', false, $context);
    $data = json_decode($json, true);
    $pages = $data['query']['pages'];
    $page = reset($pages);
    echo "'" . strtolower($city) . "' => ['" . ($page['thumbnail']['source'] ?? '') . "'],\n";
}
