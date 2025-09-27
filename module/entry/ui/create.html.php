<?php
/**
 * The create view file of entry module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     entry
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::back('GLOBAL'),
    set::title($lang->entry->create),
    to::headingActions
    (
        toolbar
        (
            a
            (
                setClass('text-darken'),
                set::href($lang->entry->helpLink),
                set('target', '_blank'),
                $lang->entry->help
            ),
            div(setClass('w-px h-3 bg-gray mx-2')),
            a
            (
                setClass('text-darken'),
                set::href($lang->entry->notifyLink),
                set('target', '_blank'),
                $lang->entry->notify
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->name),
            set::name('name'),
            set::title($lang->entry->note->name),
            set::placeholder($lang->entry->note->name),
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->code),
            set::name('code'),
            set::title($lang->entry->note->code),
            set::placeholder($lang->entry->note->code),
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->freePasswd),
            radioList
            (
                on::change()->toggleClass('.accountRow', 'hidden','$(target).val() == 1'),
                set::name('freePasswd'),
                set::items($lang->entry->freePasswdList),
                set::value(0),
                set::inline(true)
            )
        )
    ),
    formRow
    (
        setClass('accountRow'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->account),
            picker
            (
                set::placeholder($lang->entry->note->account),
                set::name('account'),
                set::items($users),
                set::required(false),
                set::value('')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->key),
            set::name('key'),
            set::value(md5((string)rand())),
            set::readonly(true)
        ),
        formGroup
        (
            a
            (
                setClass('btn ml-2 text-darken'),
                on::click('createKey()'),
                $lang->entry->createKey
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->entry->ip),
            set::title($lang->entry->note->ip),
            set::placeholder($lang->entry->note->ip),
            set::name('ip'),
            set::value('')
        ),
        formGroup
        (
            div
            (
                setClass('items-center ml-2 my-auto'),
                checkbox
                (
                    set::name('allIP'),
                    on::change('toggleAllIP'),
                    $lang->entry->note->allIP
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->entry->desc),
        control(set::type('textarea'), set::name('desc'))
    )
);

render();

