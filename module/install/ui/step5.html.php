<?php
/**
 * The step5 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

if(isset($error))
{
    h::js("zui.Modal.alert({size: '480', message: '{$error}'}).then((res) => {openUrl('" . inlink('step3') . "')});");
    render('pagebase');
    return;
}

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        isset($success) ? panel(set::title($lang->install->success), cell(icon('check-circle'), $afterSuccess)) : formPanel
        (
            setClass('bg-canvas m-auto mw-auto'),
            set::title($lang->install->getPriv),
            set::headingClass('w-96 m-auto'),
            set::submitBtnText(!empty($config->inQuickon) ? $lang->install->next : $lang->save),
            formRow
            (
                setClass('w-96 m-auto'),
                formGroup
                (
                    set::label($lang->install->company),
                    set::name('company')
                )
            ),
            formRow
            (
                setClass('w-96 m-auto hidden'),
                formGroup
                (
                    set::label($lang->install->working),
                    set::name('flow'),
                    set::items($lang->install->workingList),
                    set::value('full')
                )
            ),
            formRow
            (
                setClass('w-96 m-auto'),
                formGroup
                (
                    set::label($lang->install->account),
                    set::name('account')
                )
            ),
            formRow
            (
                setClass('w-96 m-auto'),
                formGroup
                (
                    set::label($lang->install->password),
                    password
                    (
                        set::name('password'),
                        set::placeholder($lang->install->placeholder->password)
                    )
                )
            ),
            formRow
            (
                setClass('w-96 m-auto importDemoDataRow'),
                formGroup
                (
                    set::label(' '),
                    checkbox
                    (
                        set::text($lang->install->importDemoData),
                        set::name('importDemoData'),
                        set::value(1)
                    )
                )
            ),
            contactUs()
        )
    )
);

render('pagebase');
