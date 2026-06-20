<?php

namespace App\Exports;

use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\CellAlignment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportHelper
{
    public static function exportXlsx(string $filename, array $headers, array $rows, ?string $title = null): StreamedResponse
    {
        $writer = new Writer();
        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(11)
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor('4F46E5')
            ->setCellAlignment(CellAlignment::CENTER);

        return response()->stream(function () use ($writer, $headers, $rows, $title, $headerStyle) {
            $writer->openToBrowser("php://output");

            if ($title) {
                $titleStyle = (new Style())->setFontBold()->setFontSize(14);
                $writer->addRow(Row::fromValues([$title], $titleStyle));
                $writer->addRow(Row::fromValues([''])); // empty row
            }

            // Header
            $headerRow = Row::fromValues($headers, $headerStyle);
            $writer->addRow($headerRow);

            // Data
            foreach ($rows as $row) {
                $writer->addRow(Row::fromValues($row));
            }

            $writer->close();
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
        ]);
    }
}
