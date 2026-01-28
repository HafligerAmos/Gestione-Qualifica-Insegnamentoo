<?php

namespace App\Libraries\PhpSpreadsheet\Helper;

class Migrator
{
    /**
     * Return the ordered mapping from old PHPExcel class names to new PhpSpreadsheet one.
     *
     * @return string[]
     */
    public function getMapping()
    {
        // Order matters here, we should have the deepest namespaces first (the most "unique" strings)
        $classes = [
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE\Blip::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer\SpContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer::class,
            'PHPExcel_Shared_OLE_PPS_File' => \App\Libraries\PhpSpreadsheet\Shared\OLE\PPS\File::class,
            'PHPExcel_Shared_OLE_PPS_Root' => \App\Libraries\PhpSpreadsheet\Shared\OLE\PPS\Root::class,
            'PHPExcel_Worksheet_AutoFilter_Column_Rule' => \App\Libraries\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule::class,
            'PHPExcel_Writer_OpenDocument_Cell_Comment' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Cell\Comment::class,
            'PHPExcel_Calculation_Token_Stack' => \App\Libraries\PhpSpreadsheet\Calculation\Token\Stack::class,
            'PHPExcel_Chart_Renderer_jpgraph' => \App\Libraries\PhpSpreadsheet\Chart\Renderer\JpGraph::class,
            'PHPExcel_Reader_Excel5_Escher' => \App\Libraries\PhpSpreadsheet\Reader\Xls\Escher::class,
            'PHPExcel_Reader_Excel5_MD5' => \App\Libraries\PhpSpreadsheet\Reader\Xls\MD5::class,
            'PHPExcel_Reader_Excel5_RC4' => \App\Libraries\PhpSpreadsheet\Reader\Xls\RC4::class,
            'PHPExcel_Reader_Excel2007_Chart' => \App\Libraries\PhpSpreadsheet\Reader\Xlsx\Chart::class,
            'PHPExcel_Reader_Excel2007_Theme' => \App\Libraries\PhpSpreadsheet\Reader\Xlsx\Theme::class,
            'PHPExcel_Shared_Escher_DgContainer' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DgContainer::class,
            'PHPExcel_Shared_Escher_DggContainer' => \App\Libraries\PhpSpreadsheet\Shared\Escher\DggContainer::class,
            'CholeskyDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\CholeskyDecomposition::class,
            'EigenvalueDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\EigenvalueDecomposition::class,
            'PHPExcel_Shared_JAMA_LUDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\LUDecomposition::class,
            'PHPExcel_Shared_JAMA_Matrix' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\Matrix::class,
            'QRDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\QRDecomposition::class,
            'PHPExcel_Shared_JAMA_QRDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\QRDecomposition::class,
            'SingularValueDecomposition' => \App\Libraries\PhpSpreadsheet\Shared\JAMA\SingularValueDecomposition::class,
            'PHPExcel_Shared_OLE_ChainedBlockStream' => \App\Libraries\PhpSpreadsheet\Shared\OLE\ChainedBlockStream::class,
            'PHPExcel_Shared_OLE_PPS' => \App\Libraries\PhpSpreadsheet\Shared\OLE\PPS::class,
            'PHPExcel_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\BestFit::class,
            'PHPExcel_Exponential_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\ExponentialBestFit::class,
            'PHPExcel_Linear_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\LinearBestFit::class,
            'PHPExcel_Logarithmic_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\LogarithmicBestFit::class,
            'polynomialBestFit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\PolynomialBestFit::class,
            'PHPExcel_Polynomial_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\PolynomialBestFit::class,
            'powerBestFit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\PowerBestFit::class,
            'PHPExcel_Power_Best_Fit' => \App\Libraries\PhpSpreadsheet\Shared\Trend\PowerBestFit::class,
            'trendClass' => \App\Libraries\PhpSpreadsheet\Shared\Trend\Trend::class,
            'PHPExcel_Worksheet_AutoFilter_Column' => \App\Libraries\PhpSpreadsheet\Worksheet\AutoFilter\Column::class,
            'PHPExcel_Worksheet_Drawing_Shadow' => \App\Libraries\PhpSpreadsheet\Worksheet\Drawing\Shadow::class,
            'PHPExcel_Writer_OpenDocument_Content' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Content::class,
            'PHPExcel_Writer_OpenDocument_Meta' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Meta::class,
            'PHPExcel_Writer_OpenDocument_MetaInf' => \App\Libraries\PhpSpreadsheet\Writer\Ods\MetaInf::class,
            'PHPExcel_Writer_OpenDocument_Mimetype' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Mimetype::class,
            'PHPExcel_Writer_OpenDocument_Settings' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Settings::class,
            'PHPExcel_Writer_OpenDocument_Styles' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Styles::class,
            'PHPExcel_Writer_OpenDocument_Thumbnails' => \App\Libraries\PhpSpreadsheet\Writer\Ods\Thumbnails::class,
            'PHPExcel_Writer_OpenDocument_WriterPart' => \App\Libraries\PhpSpreadsheet\Writer\Ods\WriterPart::class,
            'PHPExcel_Writer_PDF_Core' => \App\Libraries\PhpSpreadsheet\Writer\Pdf::class,
            'PHPExcel_Writer_PDF_DomPDF' => \App\Libraries\PhpSpreadsheet\Writer\Pdf\Dompdf::class,
            'PHPExcel_Writer_PDF_mPDF' => \App\Libraries\PhpSpreadsheet\Writer\Pdf\Mpdf::class,
            'PHPExcel_Writer_PDF_tcPDF' => \App\Libraries\PhpSpreadsheet\Writer\Pdf\Tcpdf::class,
            'PHPExcel_Writer_Excel5_BIFFwriter' => \App\Libraries\PhpSpreadsheet\Writer\Xls\BIFFwriter::class,
            'PHPExcel_Writer_Excel5_Escher' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Escher::class,
            'PHPExcel_Writer_Excel5_Font' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Font::class,
            'PHPExcel_Writer_Excel5_Parser' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Parser::class,
            'PHPExcel_Writer_Excel5_Workbook' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Workbook::class,
            'PHPExcel_Writer_Excel5_Worksheet' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Worksheet::class,
            'PHPExcel_Writer_Excel5_Xf' => \App\Libraries\PhpSpreadsheet\Writer\Xls\Xf::class,
            'PHPExcel_Writer_Excel2007_Chart' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Chart::class,
            'PHPExcel_Writer_Excel2007_Comments' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Comments::class,
            'PHPExcel_Writer_Excel2007_ContentTypes' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\ContentTypes::class,
            'PHPExcel_Writer_Excel2007_DocProps' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\DocProps::class,
            'PHPExcel_Writer_Excel2007_Drawing' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Drawing::class,
            'PHPExcel_Writer_Excel2007_Rels' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Rels::class,
            'PHPExcel_Writer_Excel2007_RelsRibbon' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\RelsRibbon::class,
            'PHPExcel_Writer_Excel2007_RelsVBA' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\RelsVBA::class,
            'PHPExcel_Writer_Excel2007_StringTable' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\StringTable::class,
            'PHPExcel_Writer_Excel2007_Style' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Style::class,
            'PHPExcel_Writer_Excel2007_Theme' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Theme::class,
            'PHPExcel_Writer_Excel2007_Workbook' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Workbook::class,
            'PHPExcel_Writer_Excel2007_Worksheet' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\Worksheet::class,
            'PHPExcel_Writer_Excel2007_WriterPart' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx\WriterPart::class,
            'PHPExcel_CachedObjectStorage_CacheBase' => \App\Libraries\PhpSpreadsheet\Collection\Cells::class,
            'PHPExcel_CalcEngine_CyclicReferenceStack' => \App\Libraries\PhpSpreadsheet\Calculation\Engine\CyclicReferenceStack::class,
            'PHPExcel_CalcEngine_Logger' => \App\Libraries\PhpSpreadsheet\Calculation\Engine\Logger::class,
            'PHPExcel_Calculation_Functions' => \App\Libraries\PhpSpreadsheet\Calculation\Functions::class,
            'PHPExcel_Calculation_Function' => \App\Libraries\PhpSpreadsheet\Calculation\Category::class,
            'PHPExcel_Calculation_Database' => \App\Libraries\PhpSpreadsheet\Calculation\Database::class,
            'PHPExcel_Calculation_DateTime' => \App\Libraries\PhpSpreadsheet\Calculation\DateTime::class,
            'PHPExcel_Calculation_Engineering' => \App\Libraries\PhpSpreadsheet\Calculation\Engineering::class,
            'PHPExcel_Calculation_Exception' => \App\Libraries\PhpSpreadsheet\Calculation\Exception::class,
            'PHPExcel_Calculation_ExceptionHandler' => \App\Libraries\PhpSpreadsheet\Calculation\ExceptionHandler::class,
            'PHPExcel_Calculation_Financial' => \App\Libraries\PhpSpreadsheet\Calculation\Financial::class,
            'PHPExcel_Calculation_FormulaParser' => \App\Libraries\PhpSpreadsheet\Calculation\FormulaParser::class,
            'PHPExcel_Calculation_FormulaToken' => \App\Libraries\PhpSpreadsheet\Calculation\FormulaToken::class,
            'PHPExcel_Calculation_Logical' => \App\Libraries\PhpSpreadsheet\Calculation\Logical::class,
            'PHPExcel_Calculation_LookupRef' => \App\Libraries\PhpSpreadsheet\Calculation\LookupRef::class,
            'PHPExcel_Calculation_MathTrig' => \App\Libraries\PhpSpreadsheet\Calculation\MathTrig::class,
            'PHPExcel_Calculation_Statistical' => \App\Libraries\PhpSpreadsheet\Calculation\Statistical::class,
            'PHPExcel_Calculation_TextData' => \App\Libraries\PhpSpreadsheet\Calculation\TextData::class,
            'PHPExcel_Cell_AdvancedValueBinder' => \App\Libraries\PhpSpreadsheet\Cell\AdvancedValueBinder::class,
            'PHPExcel_Cell_DataType' => \App\Libraries\PhpSpreadsheet\Cell\DataType::class,
            'PHPExcel_Cell_DataValidation' => \App\Libraries\PhpSpreadsheet\Cell\DataValidation::class,
            'PHPExcel_Cell_DefaultValueBinder' => \App\Libraries\PhpSpreadsheet\Cell\DefaultValueBinder::class,
            'PHPExcel_Cell_Hyperlink' => \App\Libraries\PhpSpreadsheet\Cell\Hyperlink::class,
            'PHPExcel_Cell_IValueBinder' => \App\Libraries\PhpSpreadsheet\Cell\IValueBinder::class,
            'PHPExcel_Chart_Axis' => \App\Libraries\PhpSpreadsheet\Chart\Axis::class,
            'PHPExcel_Chart_DataSeries' => \App\Libraries\PhpSpreadsheet\Chart\DataSeries::class,
            'PHPExcel_Chart_DataSeriesValues' => \App\Libraries\PhpSpreadsheet\Chart\DataSeriesValues::class,
            'PHPExcel_Chart_Exception' => \App\Libraries\PhpSpreadsheet\Chart\Exception::class,
            'PHPExcel_Chart_GridLines' => \App\Libraries\PhpSpreadsheet\Chart\GridLines::class,
            'PHPExcel_Chart_Layout' => \App\Libraries\PhpSpreadsheet\Chart\Layout::class,
            'PHPExcel_Chart_Legend' => \App\Libraries\PhpSpreadsheet\Chart\Legend::class,
            'PHPExcel_Chart_PlotArea' => \App\Libraries\PhpSpreadsheet\Chart\PlotArea::class,
            'PHPExcel_Properties' => \App\Libraries\PhpSpreadsheet\Chart\Properties::class,
            'PHPExcel_Chart_Title' => \App\Libraries\PhpSpreadsheet\Chart\Title::class,
            'PHPExcel_DocumentProperties' => \App\Libraries\PhpSpreadsheet\Document\Properties::class,
            'PHPExcel_DocumentSecurity' => \App\Libraries\PhpSpreadsheet\Document\Security::class,
            'PHPExcel_Helper_HTML' => \App\Libraries\PhpSpreadsheet\Helper\Html::class,
            'PHPExcel_Reader_Abstract' => \App\Libraries\PhpSpreadsheet\Reader\BaseReader::class,
            'PHPExcel_Reader_CSV' => \App\Libraries\PhpSpreadsheet\Reader\Csv::class,
            'PHPExcel_Reader_DefaultReadFilter' => \App\Libraries\PhpSpreadsheet\Reader\DefaultReadFilter::class,
            'PHPExcel_Reader_Excel2003XML' => \App\Libraries\PhpSpreadsheet\Reader\Xml::class,
            'PHPExcel_Reader_Exception' => \App\Libraries\PhpSpreadsheet\Reader\Exception::class,
            'PHPExcel_Reader_Gnumeric' => \App\Libraries\PhpSpreadsheet\Reader\Gnumeric::class,
            'PHPExcel_Reader_HTML' => \App\Libraries\PhpSpreadsheet\Reader\Html::class,
            'PHPExcel_Reader_IReadFilter' => \App\Libraries\PhpSpreadsheet\Reader\IReadFilter::class,
            'PHPExcel_Reader_IReader' => \App\Libraries\PhpSpreadsheet\Reader\IReader::class,
            'PHPExcel_Reader_OOCalc' => \App\Libraries\PhpSpreadsheet\Reader\Ods::class,
            'PHPExcel_Reader_SYLK' => \App\Libraries\PhpSpreadsheet\Reader\Slk::class,
            'PHPExcel_Reader_Excel5' => \App\Libraries\PhpSpreadsheet\Reader\Xls::class,
            'PHPExcel_Reader_Excel2007' => \App\Libraries\PhpSpreadsheet\Reader\Xlsx::class,
            'PHPExcel_RichText_ITextElement' => \App\Libraries\PhpSpreadsheet\RichText\ITextElement::class,
            'PHPExcel_RichText_Run' => \App\Libraries\PhpSpreadsheet\RichText\Run::class,
            'PHPExcel_RichText_TextElement' => \App\Libraries\PhpSpreadsheet\RichText\TextElement::class,
            'PHPExcel_Shared_CodePage' => \App\Libraries\PhpSpreadsheet\Shared\CodePage::class,
            'PHPExcel_Shared_Date' => \App\Libraries\PhpSpreadsheet\Shared\Date::class,
            'PHPExcel_Shared_Drawing' => \App\Libraries\PhpSpreadsheet\Shared\Drawing::class,
            'PHPExcel_Shared_Escher' => \App\Libraries\PhpSpreadsheet\Shared\Escher::class,
            'PHPExcel_Shared_File' => \App\Libraries\PhpSpreadsheet\Shared\File::class,
            'PHPExcel_Shared_Font' => \App\Libraries\PhpSpreadsheet\Shared\Font::class,
            'PHPExcel_Shared_OLE' => \App\Libraries\PhpSpreadsheet\Shared\OLE::class,
            'PHPExcel_Shared_OLERead' => \App\Libraries\PhpSpreadsheet\Shared\OLERead::class,
            'PHPExcel_Shared_PasswordHasher' => \App\Libraries\PhpSpreadsheet\Shared\PasswordHasher::class,
            'PHPExcel_Shared_String' => \App\Libraries\PhpSpreadsheet\Shared\StringHelper::class,
            'PHPExcel_Shared_TimeZone' => \App\Libraries\PhpSpreadsheet\Shared\TimeZone::class,
            'PHPExcel_Shared_XMLWriter' => \App\Libraries\PhpSpreadsheet\Shared\XMLWriter::class,
            'PHPExcel_Shared_Excel5' => \App\Libraries\PhpSpreadsheet\Shared\Xls::class,
            'PHPExcel_Style_Alignment' => \App\Libraries\PhpSpreadsheet\Style\Alignment::class,
            'PHPExcel_Style_Border' => \App\Libraries\PhpSpreadsheet\Style\Border::class,
            'PHPExcel_Style_Borders' => \App\Libraries\PhpSpreadsheet\Style\Borders::class,
            'PHPExcel_Style_Color' => \App\Libraries\PhpSpreadsheet\Style\Color::class,
            'PHPExcel_Style_Conditional' => \App\Libraries\PhpSpreadsheet\Style\Conditional::class,
            'PHPExcel_Style_Fill' => \App\Libraries\PhpSpreadsheet\Style\Fill::class,
            'PHPExcel_Style_Font' => \App\Libraries\PhpSpreadsheet\Style\Font::class,
            'PHPExcel_Style_NumberFormat' => \App\Libraries\PhpSpreadsheet\Style\NumberFormat::class,
            'PHPExcel_Style_Protection' => \App\Libraries\PhpSpreadsheet\Style\Protection::class,
            'PHPExcel_Style_Supervisor' => \App\Libraries\PhpSpreadsheet\Style\Supervisor::class,
            'PHPExcel_Worksheet_AutoFilter' => \App\Libraries\PhpSpreadsheet\Worksheet\AutoFilter::class,
            'PHPExcel_Worksheet_BaseDrawing' => \App\Libraries\PhpSpreadsheet\Worksheet\BaseDrawing::class,
            'PHPExcel_Worksheet_CellIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\CellIterator::class,
            'PHPExcel_Worksheet_Column' => \App\Libraries\PhpSpreadsheet\Worksheet\Column::class,
            'PHPExcel_Worksheet_ColumnCellIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\ColumnCellIterator::class,
            'PHPExcel_Worksheet_ColumnDimension' => \App\Libraries\PhpSpreadsheet\Worksheet\ColumnDimension::class,
            'PHPExcel_Worksheet_ColumnIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\ColumnIterator::class,
            'PHPExcel_Worksheet_Drawing' => \App\Libraries\PhpSpreadsheet\Worksheet\Drawing::class,
            'PHPExcel_Worksheet_HeaderFooter' => \App\Libraries\PhpSpreadsheet\Worksheet\HeaderFooter::class,
            'PHPExcel_Worksheet_HeaderFooterDrawing' => \App\Libraries\PhpSpreadsheet\Worksheet\HeaderFooterDrawing::class,
            'PHPExcel_WorksheetIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\Iterator::class,
            'PHPExcel_Worksheet_MemoryDrawing' => \App\Libraries\PhpSpreadsheet\Worksheet\MemoryDrawing::class,
            'PHPExcel_Worksheet_PageMargins' => \App\Libraries\PhpSpreadsheet\Worksheet\PageMargins::class,
            'PHPExcel_Worksheet_PageSetup' => \App\Libraries\PhpSpreadsheet\Worksheet\PageSetup::class,
            'PHPExcel_Worksheet_Protection' => \App\Libraries\PhpSpreadsheet\Worksheet\Protection::class,
            'PHPExcel_Worksheet_Row' => \App\Libraries\PhpSpreadsheet\Worksheet\Row::class,
            'PHPExcel_Worksheet_RowCellIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\RowCellIterator::class,
            'PHPExcel_Worksheet_RowDimension' => \App\Libraries\PhpSpreadsheet\Worksheet\RowDimension::class,
            'PHPExcel_Worksheet_RowIterator' => \App\Libraries\PhpSpreadsheet\Worksheet\RowIterator::class,
            'PHPExcel_Worksheet_SheetView' => \App\Libraries\PhpSpreadsheet\Worksheet\SheetView::class,
            'PHPExcel_Writer_Abstract' => \App\Libraries\PhpSpreadsheet\Writer\BaseWriter::class,
            'PHPExcel_Writer_CSV' => \App\Libraries\PhpSpreadsheet\Writer\Csv::class,
            'PHPExcel_Writer_Exception' => \App\Libraries\PhpSpreadsheet\Writer\Exception::class,
            'PHPExcel_Writer_HTML' => \App\Libraries\PhpSpreadsheet\Writer\Html::class,
            'PHPExcel_Writer_IWriter' => \App\Libraries\PhpSpreadsheet\Writer\IWriter::class,
            'PHPExcel_Writer_OpenDocument' => \App\Libraries\PhpSpreadsheet\Writer\Ods::class,
            'PHPExcel_Writer_PDF' => \App\Libraries\PhpSpreadsheet\Writer\Pdf::class,
            'PHPExcel_Writer_Excel5' => \App\Libraries\PhpSpreadsheet\Writer\Xls::class,
            'PHPExcel_Writer_Excel2007' => \App\Libraries\PhpSpreadsheet\Writer\Xlsx::class,
            'PHPExcel_CachedObjectStorageFactory' => \App\Libraries\PhpSpreadsheet\Collection\CellsFactory::class,
            'PHPExcel_Calculation' => \App\Libraries\PhpSpreadsheet\Calculation\Calculation::class,
            'PHPExcel_Cell' => \App\Libraries\PhpSpreadsheet\Cell\Cell::class,
            'PHPExcel_Chart' => \App\Libraries\PhpSpreadsheet\Chart\Chart::class,
            'PHPExcel_Comment' => \App\Libraries\PhpSpreadsheet\Comment::class,
            'PHPExcel_Exception' => \App\Libraries\PhpSpreadsheet\Exception::class,
            'PHPExcel_HashTable' => \App\Libraries\PhpSpreadsheet\HashTable::class,
            'PHPExcel_IComparable' => \App\Libraries\PhpSpreadsheet\IComparable::class,
            'PHPExcel_IOFactory' => \App\Libraries\PhpSpreadsheet\IOFactory::class,
            'PHPExcel_NamedRange' => \App\Libraries\PhpSpreadsheet\NamedRange::class,
            'PHPExcel_ReferenceHelper' => \App\Libraries\PhpSpreadsheet\ReferenceHelper::class,
            'PHPExcel_RichText' => \App\Libraries\PhpSpreadsheet\RichText\RichText::class,
            'PHPExcel_Settings' => \App\Libraries\PhpSpreadsheet\Settings::class,
            'PHPExcel_Style' => \App\Libraries\PhpSpreadsheet\Style\Style::class,
            'PHPExcel_Worksheet' => \App\Libraries\PhpSpreadsheet\Worksheet\Worksheet::class,
            'PHPExcel' => \App\Libraries\PhpSpreadsheet\Spreadsheet::class,
        ];

        $methods = [
            'MINUTEOFHOUR' => 'MINUTE',
            'SECONDOFMINUTE' => 'SECOND',
            'DAYOFWEEK' => 'WEEKDAY',
            'WEEKOFYEAR' => 'WEEKNUM',
            'ExcelToPHPObject' => 'excelToDateTimeObject',
            'ExcelToPHP' => 'excelToTimestamp',
            'FormattedPHPToExcel' => 'formattedPHPToExcel',
            'Cell::absoluteCoordinate' => 'Coordinate::absoluteCoordinate',
            'Cell::absoluteReference' => 'Coordinate::absoluteReference',
            'Cell::buildRange' => 'Coordinate::buildRange',
            'Cell::columnIndexFromString' => 'Coordinate::columnIndexFromString',
            'Cell::coordinateFromString' => 'Coordinate::coordinateFromString',
            'Cell::extractAllCellReferencesInRange' => 'Coordinate::extractAllCellReferencesInRange',
            'Cell::getRangeBoundaries' => 'Coordinate::getRangeBoundaries',
            'Cell::mergeRangesInCollection' => 'Coordinate::mergeRangesInCollection',
            'Cell::rangeBoundaries' => 'Coordinate::rangeBoundaries',
            'Cell::rangeDimension' => 'Coordinate::rangeDimension',
            'Cell::splitRange' => 'Coordinate::splitRange',
            'Cell::stringFromColumnIndex' => 'Coordinate::stringFromColumnIndex',
        ];

        // Keep '\' prefix for class names
        $prefixedClasses = [];
        foreach ($classes as $key => &$value) {
            $value = str_replace('App\Libraries\\', '\\App\Libraries\\', $value);
            $prefixedClasses['\\' . $key] = $value;
        }
        $mapping = $prefixedClasses + $classes + $methods;

        return $mapping;
    }

    /**
     * Search in all files in given directory.
     *
     * @param string $path
     */
    private function recursiveReplace($path)
    {
        $patterns = [
            '/*.md',
            '/*.php',
            '/*.phtml',
            '/*.txt',
            '/*.TXT',
        ];

        $from = array_keys($this->getMapping());
        $to = array_values($this->getMapping());

        foreach ($patterns as $pattern) {
            foreach (glob($path . $pattern) as $file) {
                $original = file_get_contents($file);
                $converted = str_replace($from, $to, $original);

                if ($original !== $converted) {
                    echo $file . " converted\n";
                    file_put_contents($file, $converted);
                }
            }
        }

        // Do the recursion in subdirectory
        foreach (glob($path . '/*', GLOB_ONLYDIR) as $subpath) {
            if (strpos($subpath, $path . '/') === 0) {
                $this->recursiveReplace($subpath);
            }
        }
    }

    public function migrate()
    {
        $path = realpath(getcwd());
        echo 'This will search and replace recursively in ' . $path . PHP_EOL;
        echo 'You MUST backup your files first, or you risk losing data.' . PHP_EOL;
        echo 'Are you sure ? (y/n)';

        $confirm = fread(STDIN, 1);
        if ($confirm === 'y') {
            $this->recursiveReplace($path);
        }
    }
}
