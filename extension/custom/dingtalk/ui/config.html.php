<?php
/**
 * The dingtalk knowledge base config view file.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     dingtalk
 * @link        https://www.zentao.net
 */
namespace zin;

// 基础配置表单
$basicFormRows = array();
foreach(explode(',', $lang->dingtalk->fields) as $field)
{
    $basicFormRows[] = formRow
    (
        formGroup
        (
            set::label($lang->dingtalk->$field),
            set::name($field),
            set::value($dingtalkConfig->$field ? $dingtalkConfig->$field : ''),
            set::placeholder($lang->dingtalk->{$field . 'Placeholder'})
        )
    );
}

div
(
    setID('mainContent'),
    setClass('row'),
    div
    (
        // 基础配置面板
        formPanel
        (
            setClass('panel'),
            set::action(createLink('dingtalk', 'saveConfig')),
            set::method('post'),
            set::id('dingtalkConfigForm'),
            h4($lang->dingtalk->config),
            $basicFormRows,
            set::actions(array('submit'))
        )
    )
);