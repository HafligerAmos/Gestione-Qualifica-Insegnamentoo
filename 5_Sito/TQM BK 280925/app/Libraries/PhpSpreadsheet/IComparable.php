<?php

namespace App\Libraries\PhpSpreadsheet;

interface IComparable
{
    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode();
}
