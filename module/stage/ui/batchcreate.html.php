<?php
/**
 * The batchCreate view file of stage module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     stage
 * @link        https://www.zentao.net
 */
namespace zin;

$percentItem = '';
if(isset($config->setPercent) and $config->setPercent == 1)
{
    $percentItem = formBatchItem(
        set::name('percent'),
        set::label($lang->stage->percent),
        set::width('150px')
        );
}
formBatchPanel
(
    formBatchItem
    (
        set::name('id'),
        set::label($lang->stage->id),
        set::control('index'),
        set::width('32px')
    ),
    formBatchItem
    (
        set::name('name'),
        set::label($lang->stage->name)
    ),
    $percentItem,
    formBatchItem
    (
        set::name('type'),
        set::label($lang->stage->type),
        set::width('150px'),
        set::control('select'),
        set::items($lang->stage->typeList)
    )
);
/* ====== Render page ====== */
render();
