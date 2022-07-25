<?php

declare(strict_types=1);

namespace Benchmarks;

use Benchmarks\Krlove\BenchmarkCommand;
use Symfony\Component\Console\Application;

require_once 'vendor/autoload.php';

$application = new Application();
$application->add($command = new BenchmarkCommand());
$application->setDefaultCommand($command->getName());
$application->run();
