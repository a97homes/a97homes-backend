<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class OctaneStartCommand extends Command
{
	// php artisan octane:start-windows --host=127.0.0.1 --port=8000 --workers=1
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'octane:start-windows {--host=127.0.0.1} {--port=8000} {--workers=1}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Start Octane server on Windows with RoadRunner';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		// Define signal constants for Windows compatibility
		if (!defined('SIGINT')) {
			define('SIGINT', 2);
		}
		if (!defined('SIGTERM')) {
			define('SIGTERM', 15);
		}
		if (!defined('SIGHUP')) {
			define('SIGHUP', 1);
		}

		$host = $this->option('host');
		$port = $this->option('port');
		$workers = $this->option('workers');

		$this->info("Starting Octane server on {$host}:{$port} with {$workers} workers...");

		// Update the RoadRunner configuration
		$this->updateRoadRunnerConfig($host, $port, $workers);

		// Start RoadRunner
		$process = new Process([base_path('rr.exe'), 'serve', '-c', base_path('.rr.yaml')]);
		$process->setTimeout(null);
		$process->setIdleTimeout(null);

		$this->info('RoadRunner server started. Press Ctrl+C to stop.');

		try {
			$process->run(function ($type, $buffer) {
				$this->line($buffer);
			});
		} catch (\Exception $e) {
			$this->error('Error starting RoadRunner: ' . $e->getMessage());
			return 1;
		}

		return 0;
	}

	/**
	 * Update RoadRunner configuration
	 */
	private function updateRoadRunnerConfig($host, $port, $workers)
	{
		// Use dynamic ports to avoid conflicts
		$rpcPort = 6000 + ($port % 100);
		$metricsPort = 2100 + ($port % 100);

		$yaml = "version: '3'
rpc:
    listen: 'tcp://127.0.0.1:{$rpcPort}'
server:
    command: 'php app.php'
    relay: pipes
http:
    address: '{$host}:{$port}'
    middleware:
        - gzip
        - static
    static:
        dir: public
        forbid:
            - .php
            - .htaccess
    pool:
        num_workers: {$workers}
        supervisor:
            max_worker_memory: 100
jobs:
    pool:
        num_workers: 2
        max_worker_memory: 100
    consume: {  }
kv:
    local:
        driver: memory
        config:
            interval: 60
metrics:
    address: '127.0.0.1:{$metricsPort}'";

		file_put_contents('.rr.yaml', $yaml);
	}
}
