<?php

namespace App\Libraries\PhpSpreadsheet\Writer;

use App\Libraries\PhpSpreadsheet\Spreadsheet;

interface IWriter
{
    /**
     * IWriter constructor.
     *
     * @param Spreadsheet $spreadsheet
     */
    public function __construct(Spreadsheet $spreadsheet);

    /**
     * Save PhpSpreadsheet to file.
     *
     * @param string $pFilename Name of the file to save
     *
     * @throws \App\Libraries\PhpSpreadsheet\Writer\Exception
     */
    public function save($pFilename);
}
