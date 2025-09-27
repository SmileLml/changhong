<?php
/**
 * The resetPWDSetting view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$menuItems = array();
if(common::hasPriv('admin', 'safe'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('admin', 'safe')),
            $lang->admin->safe->set
        )
    );
}
if(common::hasPriv('admin', 'checkWeak'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('admin', 'checkWeak')),
            $lang->admin->safe->checkWeak
        )
    );
}
if(common::hasPriv('admin', 'resetPWDSetting'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass('active'),
            set::href(createLink('admin', 'resetPWDSetting')),
            $lang->admin->resetPWDSetting
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
        setClass('resetPWDByMail-form'),
        formRow
        (
            formGroup
            (
                set::label($lang->admin->resetPWDByMail),
                set::name('resetPWDByMail'),
                set::control('radioListInline'),
                set::items($lang->admin->safe->resetPWDList),
                set::value(isset($config->resetPWDByMail) ? $config->resetPWDByMail : 0)
            )
        )
    )
);


render();

