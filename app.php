<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laravel\Octane\ApplicationFactory;
use Laravel\Octane\RequestContext;
use Laravel\Octane\RoadRunner\RoadRunnerClient;
use Laravel\Octane\Stream;
use Laravel\Octane\Worker;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Goridge\Exception\RelayException;
use Spiral\Goridge\Relay;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker as RoadRunnerWorker;

require __DIR__ . '/vendor/autoload.php';

$basePath = __DIR__;

$roadRunnerClient = new RoadRunnerClient($psr7Client = new PSR7Worker(
	new RoadRunnerWorker(Relay::create($_ENV['LARAVEL_OCTANE_ROADRUNNER_RELAY'] ?? 'pipes')),
	new ServerRequestFactory,
	new StreamFactory,
	new UploadedFileFactory,
));

$worker = null;

try {
	while ($psr7Request = $psr7Client->waitRequest()) {
		$worker = $worker ?: tap((new Worker(
			new ApplicationFactory($basePath),
			$roadRunnerClient
		)))->boot();

		if (! $psr7Request instanceof ServerRequestInterface) {
			break;
		}

		[$request, $context] = $roadRunnerClient->marshalRequest(new RequestContext([
			'psr7Request' => $psr7Request,
		]));

		$worker->handle($request, $context);
	}
} catch (Throwable $e) {
	if (! $e instanceof RelayException) {
		$worker ? report($e) : Stream::shutdown($e);
	}

	exit(1);
} finally {
	if (! is_null($worker)) {
		$worker->terminate();
	}
}
