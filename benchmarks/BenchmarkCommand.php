<?php

declare(strict_types=1);

namespace Benchmarks\Krlove;

use Krlove\Collection\Sequence\Sequence;
use SplDoublyLinkedList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure()
    {
        $this
            ->addOption('iterations', null, InputOption::VALUE_OPTIONAL, 'Number of iterations', 1000)
            ->addOption('output', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Output format', ['cli']);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $outputs = $input->getOption('output');

        foreach ($outputs as $output) {
            if (!in_array($output, ['cli', 'md'])) {
                throw new InvalidOptionException(sprintf('Output format %s is not supported', $output));
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sequence = Sequence::of('int');
        $array = [];
        $list = new SplDoublyLinkedList();

        $benchmarks = [
            [
                'operation' => 'Push element',
                'subject' => 'Array',
                'function' => function (int $i) use (&$array) {
                    $array[] = $i;
                },
            ],
            [
                'operation' => 'Push element',
                'subject' => 'SplDoublyLinkedList',
                'function' => function (int $i) use ($list) {
                    $list->push($i);
                },
            ],
            [
                'operation' => 'Push element',
                'subject' => 'Sequence',
                'function' => function (int $i) use ($sequence) {
                    $sequence->push($i);
                },
            ],
            [
                'operation' => 'Access entry by index',
                'subject' => 'Array',
                'function' => function (int $i) use (&$array) {
                    return $array[$i];
                },
            ],
            [
                'operation' => 'Access entry by index',
                'subject' => 'SplDoublyLinkedList',
                'function' => function (int $i) use ($list) {
                    return $list[$i];
                },
            ],
            [
                'operation' => 'Access entry by index',
                'subject' => 'Sequence',
                'function' => function(int $i) use ($sequence) {
                    return $sequence->get($i);
                },
            ],
        ];

        $results = [];
        foreach ($benchmarks as $benchmark) {
            $memUsageStart = memory_get_usage(true);
            $timeStart = microtime(true);
            for ($i = 0; $i < (int) $input->getOption('iterations'); $i++) {
                $benchmark['function']($i);
            }
            $memUsage = memory_get_usage(true) - $memUsageStart;
            $time = microtime(true) - $timeStart;

            $results[$benchmark['operation']][$benchmark['subject']]['mem_usage'] = $memUsage;
            $results[$benchmark['operation']][$benchmark['subject']]['time'] = $time;
        }

        $headers = ['Operation'];
        $rows = [];
        foreach ($results as $operation => $operationData) {
            $row = [];
            $row[] = $operation;
            foreach ($operationData as $subject => $subjectData) {
                $headers[] = new TableCell($subject, ['colspan' => 2]);
                $row[] = $this->formatBytes($subjectData['mem_usage']);
                $row[] = $this->formatTime($subjectData['time']);
            }

            $rows[] = $row;
        }
        $headers = array_unique($headers);

        $subHeader = [];
        foreach ($rows[0] as $i => $value) {
            if ($i === 0) {
                $subHeader[] = '';
            } else {
                $subHeader[] = $i % 2 === 0 ? 'Time' : 'Memory';
            }
        }
        array_unshift($rows, $subHeader, new TableSeparator());

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows);
        $table->render();

        return 0;
    }

    private function formatBytes(int $bytes): string {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function formatTime(float $time): string
    {
        return round($time, 4) . 's';
    }
}
