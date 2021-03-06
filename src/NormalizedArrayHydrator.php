<?php

namespace Fesor\Doctrine\CustomHydrator;

use Doctrine\ORM\Internal\Hydration\ArrayHydrator;

class NormalizedArrayHydrator extends ArrayHydrator
{
    const NAME = 'NormalizedArrayHydrator';

    protected function hydrateRowData(array $row, array &$result)
    {
        parent::hydrateRowData($row, $result);
        foreach ($result as &$row) {
            $this->postProcessRow($row);
        }
    }
    private function postProcessRow(&$row)
    {
        $aggregated = [];
        foreach ($row as $key => $value) {
            $this->split($key, $value, $aggregated);
        }
        $row = [];
        foreach ($aggregated as $key => $aggregatedValue) {
            $row[$key] = $aggregatedValue;
        }
    }
    private function split($key, &$value, &$aggregated)
    {
        if (strpos($key, '.') === false) {
            $aggregated[$key] = $value;
            return;
        }
        $path = explode('.', $key, 2);
        $this->split($path[1], $value, $aggregated[$path[0]]);
    }
}
