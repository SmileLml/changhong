<?php
/**
 * The safe view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$formRows = array();
if($objectType == 'requirement') $objectType = 'story';
foreach($fields[$objectType] as $field)
{
    $formRows[] = formRow
    (
        formGroup
        (
            set::label($lang->$objectType->{$field}),
            textarea
            (
                set::name($field),
                set::value($rules->$field ? $rules->$field : ''),
                set::rows(1)
            )
        )
    );
}
foreach($customFields as $field)
{
     $formRows[] = formRow
    (
        formGroup
        (
            set::label($field['name']),
            textarea
            (
                set::name($field['field']),
                set::value($rules->{$field['field']} ? $rules->{$field['field']} : ''),
                set::rows(1)
            )
        )
    );
}
foreach($lang->ai->ratingRules->remark as $field => $value)
{
     $formRows[] = formRow
    (
        formGroup
        (
            set::label($value),
            textarea
            (
                set::name($field),
                set::value($rules->$field ? $rules->$field : ''),
                set::rows(1)
            )
        )
    );
}

$methodName = $app->getMethodName();
$menuItems = array();
if(common::hasPriv('ai', 'requirementRatingRule'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($methodName == 'requirementratingrule' ? 'active' : ''),
            set::href(createLink('ai', 'requirementRatingRule')),
            $lang->ai->ratingRules->category['requirement']
        )
    );
}
if(common::hasPriv('ai', 'storyRatingRule'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($methodName == 'storyratingrule' ? 'active' : ''),
            set::href(createLink('ai', 'storyRatingRule')),
            $lang->ai->ratingRules->category['story']
        )
    );
}
if(common::hasPriv('ai', 'taskRatingRule'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($methodName == 'taskratingrule' ? 'active' : ''),
            set::href(createLink('ai', 'taskRatingRule')),
            $lang->ai->ratingRules->category['task']
        )
    );
}
if(common::hasPriv('ai', 'bugRatingRule'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($methodName == 'bugratingrule' ? 'active' : ''),
            set::href(createLink('ai', 'bugRatingRule')),
            $lang->ai->ratingRules->category['bug']
        )
    );
}

div
(
    setID('mainContent'),
    setClass('row has-sidebar-left'),
    $menuItems ? sidebar
    (
        set::showToggle(false),
        div
        (
            setClass('cell p-2.5 bg-white'),
            menu
            (
                $menuItems
            )
        )
    ) : null,
    formPanel
    (
        setClass('admin-safe-form'),
        $formRows
    )
);
render();

