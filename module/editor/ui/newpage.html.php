<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

h::css(".panel .panel-heading {justify-content: flex-start;}");

formPanel
(
    to::heading
    (
        icon('plus'),
        span(setClass('font-bold'), $lang->editor->newPage)
    ),
    set::actions(array('submit')),
    formGroup
    (
        set::style(array('align-items' => 'center')),
        set::label($lang->editor->filePath),
        set::control(false),
        h::code($filePath)
    ),
    formGroup
    (
        set::label($lang->editor->pageName),
        set::control('input'),
        set::name('fileName'),
        set::placeholder($lang->editor->examplePHP)
    )
);

render('pagebase');
