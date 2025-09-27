<?php
/**
 * The exportchart view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang <wangzemei@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('defaultFileName',  $lang->execution->report->untitled);
jsVar('errorExportChart', $lang->execution->report->errorExportChart);
formPanel
(
    set::formID('exportChartForm'),
    set::title($lang->export),
    set::actions(array()),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('fileName'),
            set::label($lang->setFileName)
        ),
        btn
        (
            on::click()->call('exportExecutionChart'),
            set::text($lang->save),
            set::type('primary')
        )
    )
);
