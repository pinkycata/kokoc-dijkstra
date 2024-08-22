<?php declare(strict_types=1);

namespace App;

use App\DTO\DijkstraResultDTO;
use Exception;

class Dijkstra
{
    private array $distanceToVertexList = [];
    private array $previousVertexList = [];
    private array $unvisitedVertexList = [];

    /**
     * @param array $graph
     */
    public function __construct(private array $graph) {}

    /**
     * @param string $startVertex
     * @param string $endVertex
     *
     * @return DijkstraResultDTO
     *
     * @throws Exception
     */
    public function findShortestPath(string $startVertex, string $endVertex): DijkstraResultDTO
    {
        $this->validateGraph($startVertex, $endVertex);
        $this->setupDefaultProperties();
        $this->distanceToVertexList[$startVertex] = 0;

        while (!empty($this->unvisitedVertexList)) {
            $currentVertex = $this->getVertexWithMinWeight();
            if ($this->distanceToVertexList[$currentVertex] === PHP_INT_MAX) {
                if ($currentVertex === $endVertex) {
                    throw new \Exception('Путь не существует');
                }
                break;
            }

            unset($this->unvisitedVertexList[$currentVertex]);
            $this->processComparingVertexWeight($currentVertex);
        }

        return new DijkstraResultDTO(
            $this->distanceToVertexList[$endVertex],
            $this->preparePath($this->previousVertexList, $endVertex)
        );
    }

    /**
     * сравнение веса текущей вершины с соседне, для улучшения известного кратчайшего пути
     * @param string $currentVertex
     *
     * @return void
     */
    private function processComparingVertexWeight(string $currentVertex): void
    {
        foreach ($this->graph[$currentVertex] as $neighborVertex => $weight) {
            if (isset($this->unvisitedVertexList[$neighborVertex])) {
                $alternativeWeight = $this->distanceToVertexList[$currentVertex] + $weight;
                if ($alternativeWeight < $this->distanceToVertexList[$neighborVertex]) {
                    $this->distanceToVertexList[$neighborVertex] = $alternativeWeight;
                    $this->previousVertexList[$neighborVertex] = $currentVertex;
                }
            }
        }
    }

    /**
     * @param string $startVertex
     * @param string $endVertex
     *
     * @return void
     *
     * @throws Exception
     */
    private function validateGraph(string $startVertex, string $endVertex): void
    {
        if (!array_key_exists($endVertex, $this->graph)) {
            throw new Exception('Такой вершины не существует: ' . $endVertex);
        }

        if (empty($this->graph[$startVertex]) && $startVertex != $endVertex) {
            throw new Exception('Начальная вершина не имеет путей: ' . $startVertex);
        }
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    private function setupDefaultProperties(): void
    {
        foreach ($this->graph as $vertex => $edgeList) {
            $this->distanceToVertexList[$vertex] = PHP_INT_MAX;
            $this->previousVertexList[$vertex] = null;
            $this->unvisitedVertexList[$vertex] = true;
            $this->checkEdgeWeight($edgeList);
        }
    }

    /**
     * @param array $edgeList
     *
     * @return void
     *
     * @throws Exception
     */
    private function checkEdgeWeight(array $edgeList): void
    {
        foreach ($edgeList as $weight) {
            if ($weight < 0) {
                throw new \Exception('Вес ребра не может быть отрицательным');
            }
        }
    }

    /**
     * @param array $previousVertexList
     * @param string $endVertex
     *
     * @return array
     */
    private function preparePath(array $previousVertexList, string $endVertex): array
    {
        $path = [];
        $currentVertex = $endVertex;
        while ($currentVertex !== null) {
            $path[] = $currentVertex;
            $currentVertex = $previousVertexList[$currentVertex] ?? null;
        }

        return array_reverse($path);
    }

    /**
     * Ищем вершину с минимальным расстоянием
     * @return string
     */
    private function getVertexWithMinWeight(): string
    {
        $currentVertex = null;
        foreach ($this->unvisitedVertexList as $vertex => $value) {
            if ($currentVertex === null || $this->distanceToVertexList[$vertex] < $this->distanceToVertexList[$currentVertex]) {
                $currentVertex = $vertex;
            }
        }

        return $currentVertex;
    }
}