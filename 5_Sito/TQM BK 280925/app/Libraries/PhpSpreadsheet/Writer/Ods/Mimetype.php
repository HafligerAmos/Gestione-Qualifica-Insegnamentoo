<?php

namespace App\Libraries\PhpSpreadsheet\Writer\Ods;

use App\Libraries\PhpSpreadsheet\Spreadsheet;

class Mimetype extends WriterPart
{
    /**
     * Write mimetype to plain text format.
     *
     * @param Spreadsheet $spreadsheet
     *
     * @throws \App\Libraries\PhpSpreadsheet\Writer\Exception
     *
     * @return string XML Output
     */
    public function write(Spreadsheet $spreadsheet = null)
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }
}
