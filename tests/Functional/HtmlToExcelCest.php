<?php

declare(strict_types=1);

namespace app\tests\Functional;

use app\tests\Support\FunctionalTester;

final class HtmlToExcelCest
{
    public function openModuleIndexPage(FunctionalTester $I)
    {
        $I->amOnRoute('htmltoexcel/default/index');
        $I->see('HTML Table to Excel Export Demo', 'h1');
        $I->see('Имя файла:');
        $I->see('📥 Скачать Excel', 'button');
    }

    public function submitHtmlExport(FunctionalTester $I)
    {
        $I->amOnRoute('htmltoexcel/default/index');
        $I->submitForm('#export-form', [
            'html' => '<table><tr><td>Test Cell</td></tr></table>',
            'filename' => 'output.xlsx',
            'format' => 'Xlsx',
        ]);
        $I->seeResponseCodeIs(200);
    }
}
