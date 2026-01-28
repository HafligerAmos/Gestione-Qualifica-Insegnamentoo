<?php

namespace App\Libraries\PhpSpreadsheet\Chart\Renderer;

use App\Libraries\PhpSpreadsheet\Chart\Chart;

interface IRenderer
{
    /**
     * IRenderer constructor.
     *
     * @param \App\Libraries\PhpSpreadsheet\Chart\Chart $chart
     */
    public function __construct(Chart $chart);

    /**
     * Render the chart to given file (or stream).
     *
     * @param string $filename Name of the file render to
     *
     * @return bool true on success
     */
    public function render($filename);
}
