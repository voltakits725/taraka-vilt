<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $agent = new App\Ai\Agents\MenuAssistantAgent();
    $response = $agent->prompt('Tes', provider: 'openai');
    echo 'SUCCESS OpenAI: ' . $response->text . "\n";
} catch (\Exception $e) {
    echo 'ERROR OpenAI: ' . $e->getMessage() . "\n";
}

try {
    $agent = new App\Ai\Agents\MenuAssistantAgent();
    $response = $agent->prompt('Tes', provider: 'anthropic');
    echo 'SUCCESS Anthropic: ' . $response->text . "\n";
} catch (\Exception $e) {
    echo 'ERROR Anthropic: ' . $e->getMessage() . "\n";
}

try {
    $agent = new App\Ai\Agents\MenuAssistantAgent();
    $response = $agent->prompt('Tes', provider: 'groq');
    echo 'SUCCESS Groq: ' . $response->text . "\n";
} catch (\Exception $e) {
    echo 'ERROR Groq: ' . $e->getMessage() . "\n";
}
