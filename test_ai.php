<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $agent = new App\Ai\Agents\MenuAssistantAgent();
    $participant = (object) ['id' => null, 'session_id' => '123'];
    $agent->forUser($participant);
    $response = $agent->prompt("tes", provider: 'gemini');
    echo "Response: " . $response->text;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
