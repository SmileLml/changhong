<?php
/**
 * The exportchart view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('defaultFileName', $lang->task->report->untitled);
jsVar('errorExportChart', $lang->task->report->errorExportChart);
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
            on::click()->call('exportChart'),
            set::text($lang->save),
            set::type('primary')
        )
    )
);
