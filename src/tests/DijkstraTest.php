<?php
declare(strict_types=1);

namespace App\Test;

use App\Dijkstra;
use Exception;
use PHPUnit\Framework\TestCase;

class DijkstraTest extends TestCase
{
    /**
     * @return void
     *
     * @throws Exception
     */
    public function testFindShortestPath(): void
    {
        $graph = [
            'A' => ['B' => 5, 'C' => 1],
            'B' => ['A' => 5, 'C' => 2, 'D' => 1],
            'C' => ['A' => 1, 'B' => 2, 'D' => 4],
            'D' => ['B' => 1, 'C' => 4],
            'F' => ['D' => 6]
        ];

        $dijkstra = new Dijkstra($graph);
        $dijkstraResultDTO = $dijkstra->findShortestPath('A', 'B');

        $this->assertEquals(3, $dijkstraResultDTO->getDistance());
        $this->assertEquals(['A', 'C', 'B'], $dijkstraResultDTO->getPath());
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testFindShortestPathWithNegativeWeight(): void
    {
        $graph = [
            'A' => ['B' => -1, 'C' => 1],
            'B' => ['A' => -1, 'C' => 2, 'D' => 1],
            'C' => ['A' => 1, 'B' => 2, 'D' => 4, 'E' => 8],
            'D' => ['B' => 1, 'C' => 4, 'E' => 3, 'F' => 6],
            'E' => ['C' => 8, 'D' => 3],
            'F' => ['D' => 6]
        ];

        $dijkstra = new Dijkstra($graph);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Вес ребра не может быть отрицательным');
        $dijkstra->findShortestPath('A', 'F');
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testFindShortestPathWithNonExistentVertex(): void
    {
        $graph = [
            'A' => ['B' => 5, 'C' => 1],
            'B' => ['A' => 5, 'C' => 2, ],
        ];

        $dijkstra = new Dijkstra($graph);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Такой вершины не существует');
        $dijkstra->findShortestPath('A', 'Z');
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testFindShortestPathWithNoPath(): void
    {
        $graph = [
            'A' => ['B' => 5],
            'B' => ['A' => 5],
            'C' => [],
            'E' => []
        ];

        $dijkstra = new Dijkstra($graph);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Путь не существует');
        $dijkstra->findShortestPath('A', 'C');
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testFindShortestPathWithIsolatedVertex(): void
    {
        $graph = [
            'A' => ['B' => 5],
            'B' => ['A' => 5],
            'C' => [],
            'D' => [],
            'E' => []
        ];

        $dijkstra = new Dijkstra($graph);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Начальная вершина не имеет путей: C');
        $dijkstra->findShortestPath('C', 'A');
    }
}