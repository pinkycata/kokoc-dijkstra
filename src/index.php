<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Dijkstra;

$graph = [
    'A' => ['B' => 1, 'C' => 4],
    'B' => ['A' => 1, 'C' => 2, 'D' => 5],
    'C' => ['A' => 4, 'B' => 2, 'D' => 1],
    'D' => ['B' => 5, 'C' => 1],
    'E' => []
];

$dijkstra = new Dijkstra($graph);

try {
    $dijkstraResultDTO = $dijkstra->findShortestPath('A', 'D');
    echo sprintf(
        'Расстояние: %s, Путь: %s',
        $dijkstraResultDTO->getDistance(),
        implode(' -> ', $dijkstraResultDTO->getPath())
    );
} catch (Throwable $exception) {
    echo 'Произошла ошибка: ' . $exception->getMessage();
}
