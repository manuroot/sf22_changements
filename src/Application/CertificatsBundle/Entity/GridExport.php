<?php

namespace Application\CertificatsBundle\Entity;
use APY\DataGridBundle\Grid\Export\Export;

class GridExport extends Export
{
    protected $fileExtension = 'csv';

    protected $mimeType = 'text/comma-separated-values';

    public function __construct($tilte, $fileName = 'export', $params = array(), $charset = 'UTF-8')
    {
        $this->parameters['delimiter'] = (isset($params['delimiter'])) ? $params['delimiter'] : ',';

        parent::__construct($tilte, $fileName, $params, $charset);
    }

    public function computeData($grid)
    {
        $data = $this->getFlatGridData($grid);

        // Array to dsv
        $outstream = fopen("php://temp", 'r+');

        foreach ($data as $line) {
            fputcsv($outstream, $line, $this->parameters['delimiter'], '"');
        }

        rewind($outstream);

        $content = '';
        while (($buffer = fgets($outstream)) !== false) {
            $content .= $buffer;
        }

        fclose($outstream);

        $this->content = $content;
    }
}