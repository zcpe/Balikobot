<?php
require 'vendor/autoload.php';

Flight::route('/', function() {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<title>Balikobot test app</title>
</head>
<body>
<pre>
Use "/forecast" endpoint with body like

{
    "city": "Praha"
}

Supported cities are:
• Praha
• Brno
• Ostrava
• Olomouc
• Plzeň
• Pardubice

Example:
curl -X POST http://localhost:8000/forecast -H 'Content-Type: application/json' -d '{"city" : "Praha"}'
</pre>
</body>
</html>
HTML;
});

Flight::route('/forecast', function() {
    $application = new Balikobot\Application();

    try {
        $forecast = $application->getCityForecast(Flight::request()->data->city ?? '');
        Flight::json($forecast->toArray());
    } catch (Throwable $t) {
        Flight::response()->status($t->getCode());
        Flight::json([
            'error' => $t->getMessage(),
        ]);
    }

    echo PHP_EOL;
});

Flight::start();
