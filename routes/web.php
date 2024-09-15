<?php

use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\RenderTextFormat;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/metrics', function () {
    // Create the CollectorRegistry instance with an InMemory storage adapter
    $adapter = new InMemory();
    $registry = new CollectorRegistry($adapter);

    // Register some metrics
    $counter = $registry->getOrRegisterCounter('my_namespace', 'my_custom_metric', 'My custom counter', ['type']);
    $counter->inc(['example']);
    
    $gauge = $registry->getOrRegisterGauge('my_namespace', 'my_gauge_metric', 'My gauge metric');
    $gauge->set(42);

    $histogram = $registry->getOrRegisterHistogram('my_namespace', 'my_timing_metric', 'My timing metric', ['type']);
    $histogram->observe(350, ['example']);

    // Render the metrics to the Prometheus text format
    $renderer = new RenderTextFormat();
    $result = $renderer->render($registry->getMetricFamilySamples());

    return response($result, 200, ['Content-Type' => 'text/plain']);
});