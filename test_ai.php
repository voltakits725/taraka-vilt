<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $res = \Laravel\Ai\AnonymousAgent::make('hi', [], [])->prompt('test', provider: 'gemini')->text();
    echo "SUCCESS: " . $res;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    if ($e->getPrevious()) {
        echo "PREVIOUS: " . $e->getPrevious()->getMessage() . "\n";
        if (method_exists($e->getPrevious(), 'response')) {
            echo "RESPONSE: " . $e->getPrevious()->response->body() . "\n";
        }
    }
}
