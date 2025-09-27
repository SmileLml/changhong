<?php
/**
 * The creategroup view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($permissionError))
{
    jsCall('alertJump', array($permissionError, $errorJump));
    return;
}

$publicTip = "<span id='publicTip' class='text-danger'>" . $lang->gitlab->group->publicTip . '</span>';
jsVar('publicTip', $publicTip);

formPanel
(
    set::title($lang->gitlab->group->create),
    set::labelWidth($app->clientLang == 'zh-cn' ? '8em' : '13em'),
    formGroup
    (
        set::name('name'),
        set::label($lang->gitlab->group->name),
        set::placeholder($lang->gitlab->group->name),
        set::required(true),
        set::width('1/2')
    ),
    formGroup
    (
        set::label($lang->gitlab->group->path),
        set::width('1/2'),
        set::required(true),
        inputGroup
        (
            $gitlab->url . '/',
            input
            (
                set::name('path'),
                set::placeholder($lang->gitlab->group->path)
            )
        )
    ),
    formGroup
    (
        set::label($lang->gitlab->group->description),
        set::name('description'),
        set::placeholder($lang->gitlab->group->description),
        set::width('1/2')
    ),
    formGroup
    (
        set::label($lang->gitlab->group->visibility),
        set::name('visibility'),
        set::control('radioList'),
        set::items($lang->gitlab->group->visibilityList),
        set::value('private'),
        on::change('onAclChange')
    ),
    formGroup
    (
        set::label($lang->gitlab->group->permission),
        set::name('request_access_enabled'),
        set::control(array('control' => 'checkbox', 'value' => '1', 'text' => $lang->gitlab->group->requestAccessEnabledTip, 'checked' => true))
    ),
    formGroup
    (
        set::label($lang->gitlab->group->lfsEnabled),
        set::name('lfs_enabled'),
        set::control(array('control' => 'checkbox', 'value' => '1' , 'text' => $lang->gitlab->group->lfsEnabledTip, 'checked' => true))
    ),
    formGroup
    (
        set::label($lang->gitlab->group->projectCreationLevel),
        set::width('1/2'),
        picker
        (
            set::name('project_creation_level'),
            set::items($lang->gitlab->group->projectCreationLevelList),
            set::value('developer'),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->gitlab->group->subgroupCreationLevel),
        set::width('1/2'),
        picker
        (
            set::name('subgroup_creation_level'),
            set::items($lang->gitlab->group->subgroupCreationLevelList),
            set::value('maintainer'),
            set::required(true)
        )
    )
);

render();

