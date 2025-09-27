<?php
/**
 * The create view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

h::importJs('js/misc/base64.js');
jsVar('hostID', $MR->hostID);
jsVar('projectID', $MR->sourceProject);

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

$noEditBranch = $MR->status == 'merged' || $MR->status == 'closed' || $host->type == 'gogs';

formPanel
(
    set::title($lang->mr->edit),
    formRow(formGroup
    (
        set::width('1/2'),
        set::label($lang->repo->common),
        set::value($sourceProject),
        set::control('static')
    ), formGroup
    (
        setClass('hidden'),
        set::label($lang->mr->targetProject),
        set::value($targetProject),
        set::control('static')
    )),
    formRow(formGroup
    (
        set::width('1/2'),
        set::label($lang->mr->sourceBranch),
        set::value($MR->sourceBranch),
        set::control('static')
    ), formGroup
    (
        set::width('1/2'),
        !$noEditBranch ? set::required(true) : null,
        set::label($lang->mr->targetBranch),
        set::value($MR->targetBranch),
        !$noEditBranch ? set::name('targetBranch') : null,
        !$noEditBranch ? set::items($branches) : set::control('static')
    ), $noEditBranch ? input
    (
        set::type('hidden'),
        set::name('targetBranch'),
        set::value($MR->targetBranch)
    ) : null),
    formGroup
    (
        set::required(true),
        set::name('title'),
        set::label($lang->mr->title),
        set::value($MR->title)
    ),
    formGroup
    (
        set::disabled($noEditBranch),
        set::width('1/2'),
        set::required(true),
        set::name('assignee'),
        set::label($lang->mr->reviewer),
        set::control('picker'),
        set::items($users),
        set::value($MR->assignee)
    ),
    $noEditBranch ? input
    (
        set::type('hidden'),
        set::name('assignee'),
        set::value($MR->assignee)
    ) : null,
    formRow
    (
        formGroup
        (
            set::disabled($noEditBranch),
            set::label($lang->mr->submitType),
            set::name('needCI'),
            set::width('270px'),
            set::control(array('control' => 'checkbox', 'text' => $lang->mr->needCI, 'value' => '1', 'checked' => $MR->needCI == '1')),
            on::change('onNeedCiChange')
        ),
        $noEditBranch ? input
        (
            set::type('hidden'),
            set::name('needCI'),
            set::value($MR->needCI)
        ) : null,
        formGroup
        (
            set::name('removeSourceBranch'),
            set::width('150px'),
            set::control(array('control' => 'checkbox', 'text' => $lang->mr->removeSourceBranch, 'value' => '1', 'checked' => $MR->removeSourceBranch == '1')),
            set::disabled($noEditBranch)
        ),
        $noEditBranch ? input
        (
            set::type('hidden'),
            set::name('removeSourceBranch'),
            set::value($MR->removeSourceBranch)
        ) : null,
        formGroup
        (
            set::disabled($noEditBranch),
            set::name('squash'),
            set::control(array('control' => 'checkbox', 'text' => $lang->mr->squash, 'value' => '1', 'checked' => $MR->squash == '1')),
            btn(icon('help'), setClass('text-gray size-sm mt-1 ghost'), set('data-placement', 'right'), set('data-type', 'white'), set('data-class-name', 'text-gray border border-light'), toggle::tooltip(array('title' => $lang->mr->squashHelp)))
        ),
        $noEditBranch ? input
        (
            set::type('hidden'),
            set::name('squash'),
            set::value($MR->squash)
        ) : null
    ),
    formRow
    (
        $MR->needCI == '1' ? null : setClass('hidden'),
        formGroup
        (
            set::disabled($noEditBranch),
            set::width('1/2'),
            set::required(true),
            set::name('jobID'),
            set::label($lang->mr->pipeline),
            set::control('picker'),
            set::items($jobList),
            set::value($MR->jobID)
        ),
        $noEditBranch ? input
        (
            set::type('hidden'),
            set::name('jobID'),
            set::value($MR->jobID)
        ) : null
    ),
    formGroup
    (
        set::name('description'),
        set::label($lang->mr->description),
        set::control('textarea'),
        set::value($MR->description)
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::name('repoID'),
            set::label($lang->devops->repo),
            set::value($MR->repoID)
        )
    )
);

render();
