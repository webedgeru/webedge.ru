<?php

declare(strict_types=1);

namespace app\tests\Unit;

use Yii;
use yii2\htmltoexcel\Module;
use yii2\htmltoexcel\components\HtmlToExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

final class HtmlToExcelTest extends \Codeception\Test\Unit
{
    public function testModuleRegistration(): void
    {
        $module = Yii::$app->getModule('htmltoexcel');
        self::assertInstanceOf(Module::class, $module);
    }

    public function testComponentRegistration(): void
    {
        $component = Yii::$app->get('htmlToExcel');
        self::assertInstanceOf(HtmlToExcel::class, $component);
    }

    public function testParseHtmlTable(): void
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('htmltoexcel');

        $html = <<<HTML
<table data-sheet-name="TestSheet">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><b>Item A</b></td>
            <td data-type="numeric">100.50</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Item B</td>
            <td data-type="numeric">200.00</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total</td>
            <td data-type="formula">=SUM(C2:C3)</td>
        </tr>
    </tfoot>
</table>
HTML;

        $spreadsheet = $module->parse($html);
        self::assertInstanceOf(Spreadsheet::class, $spreadsheet);

        $sheet = $spreadsheet->getActiveSheet();
        self::assertSame('TestSheet', $sheet->getTitle());
        self::assertSame('ID', $sheet->getCell('A1')->getValue());
        self::assertSame('Name', $sheet->getCell('B1')->getValue());
        self::assertSame('Amount', $sheet->getCell('C1')->getValue());
        self::assertEquals(1, $sheet->getCell('A2')->getValue());
        self::assertSame('Item A', $sheet->getCell('B2')->getValue());
        self::assertEquals(100.50, $sheet->getCell('C2')->getValue());
        self::assertSame('=SUM(C2:C3)', $sheet->getCell('C4')->getValue());
    }
}
