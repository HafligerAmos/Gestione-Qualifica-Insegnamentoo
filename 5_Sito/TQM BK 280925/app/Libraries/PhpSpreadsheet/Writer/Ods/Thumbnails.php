<?php

namespace App\Libraries\PhpSpreadsheet\Writer\Ods;

use App\Libraries\PhpSpreadsheet\Spreadsheet;

class Thumbnails extends WriterPart
{
    /**
     * Write Thumbnails/thumbnail.png to PNG format.
     *
     * @param Spreadsheet $spreadsheet
     *
     * @throws \App\Libraries\PhpSpreadsheet\Writer\Exception
     *
     * @return string XML Output
     */
    public function writeThumbnail(Spreadsheet $spreadsheet = null)
    {
        return '';
    }
}
