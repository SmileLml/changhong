<?php
/**
 * The create view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

jsVar('weekend', $config->execution->weekend);
jsVar('productID', $product->id);
jsVar('lastLang', $lang->productplan->last);
jsVar('parentPlanID', $parent);
jsVar('parentList', $parentList);

if($parent)
{
    foreach($branches as $branchID => $branchName)
    {
        if(strpos(",$parentPlan->branch,", ",$branchID,") === false) unset($branches[$branchID]);
    }
}

formPanel
(
    setID('createPlanPanel'),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::title($parent ? $lang->productplan->createChildren : $lang->productplan->create),
    $parent ? formGroup
    (
        set::className('items-center'),
        set::label($lang->productplan->parent),
        span($parentPlan->title)
    ) : null,
    !$parent && !$product->shadow ? formGroup
    (
        set::className('items-center'),
        set::label($lang->productplan->product),
        $product->name
    ) : null,
    !$parent ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->parent),
        picker
        (
            set::name('parent'),
            set::items($parentPlanPairs),
            $product->type != 'normal' ? on::change('loadBranches') : ''
        )
    ) : formHidden('parent', $parent),
    !$product->shadow && $product->type != 'normal' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->branch),
        set::required(true),
        picker
        (
            set::name('branch[]'),
            set::items($branches),
            set::multiple(true),
            on::change('loadTitle')
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->productplan->title),
            set::name('title'),
            isset($aiWeightField['title']) ? set::labelHint(empty($aiWeightField['title']['rule']) ? ' ' : $aiWeightField['title']['rule']) : null,
            isset($aiWeightField['title']) ? (empty($aiWeightField['title']['rule']) ? set::labelHintClass('ai-weight close-tip') : set::labelHintClass('ai-weight')) : null,
            isset($aiWeightField['title']) ? set::labelHintProps(array('control' => 'text', 'text' => empty($aiWeightField['title']['weight']) ? '0.0' : $aiWeightField['title']['weight'])) : null
        ),
        $lastPlan ? formGroup
        (
            set::width('1/2'),
            setClass('items-center text-gray'),
            span
            (
                setClass('ml-4'),
                setID('lastTitleBox'),
                '(' . $lang->productplan->last . ': ' . $lastPlan->title . ')'
            )
        ) : null
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->productplan->begin),
            isset($aiWeightField['begin']) ? set::labelHint(empty($aiWeightField['begin']['rule']) ? ' ' : $aiWeightField['begin']['rule']) : null,
            isset($aiWeightField['begin']) ? (empty($aiWeightField['begin']['rule']) ? set::labelHintClass('ai-weight close-tip') : set::labelHintClass('ai-weight')) : null,
            isset($aiWeightField['begin']) ? set::labelHintProps(array('control' => 'text', 'text' => empty($aiWeightField['begin']['weight']) ? '0.0' : $aiWeightField['begin']['weight'])) : null,
            datepicker
            (
                setID('begin'),
                set::name('begin'),
                set::value(formatTime($begin))
            )
        ),
        formGroup
        (
            setClass('items-center'),
            set::width('2/3'),
            checkbox
            (
                set::name('future'),
                set::text($lang->productplan->future),
                set::value(1),
                set::rootClass('ml-4'),
                on::change('toggleDateBox')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->productplan->end),
            isset($aiWeightField['end']) ? set::labelHint(empty($aiWeightField['end']['rule']) ? ' ' : $aiWeightField['end']['rule']) : null,
            isset($aiWeightField['end']) ? (empty($aiWeightField['end']['rule']) ? set::labelHintClass('ai-weight close-tip') : set::labelHintClass('ai-weight')) : null,
            isset($aiWeightField['end']) ? set::labelHintProps(array('control' => 'text', 'text' => empty($aiWeightField['end']['weight']) ? '0.0' : $aiWeightField['end']['weight'])) : null,
            set::control('date'),
            setID('end'),
            set::name('end')
        ),
        formGroup
        (
            set::width('2/3'),
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->productplan->endList),
                on::change('computeEndDate')
            )
        )
    ),
    formGroup
    (
        set::label($lang->productplan->desc),
        isset($aiWeightField['desc']) ? set::labelHint(empty($aiWeightField['desc']['rule']) ? ' ' : $aiWeightField['desc']['rule']) : null,
        isset($aiWeightField['desc']) ? (empty($aiWeightField['desc']['rule']) ? set::labelHintClass('ai-weight close-tip') : set::labelHintClass('ai-weight')) : null,
        isset($aiWeightField['desc']) ? set::labelHintProps(array('control' => 'text', 'text' => empty($aiWeightField['desc']['weight']) ? '0.0' : $aiWeightField['desc']['weight'])) : null,
        set::name('desc'),
        set::control('editor'),
        set::rows(10)
    ),
    formHidden('product', $product->id)
);

/* ====== Render page ====== */
render();
