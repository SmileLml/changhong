<?php
/**
 * The start view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */
jsVar('confirmFinish', $lang->task->confirmFinish);
jsVar('noticeTaskStart', $lang->task->noticeTaskStart);

/* zin: Set variables to define control for form. */
$assignedToControl = '';
if($task->mode == 'linear')
{
    $assignedToControl = inputGroup(
        set::className('no-background'),
        picker
        (
            set::name('assignedTo'),
            set::items($users),
            set::value($assignedTo),
            set::required(true),
            set::disabled(true)
        ),
        input
        (
            set::className('hidden'),
            set::name('assignedTo'),
            set::value($assignedTo)
        )
    );
}
elseif($canRecordEffort)
{
    $assignedToControl = picker(
        set::name('assignedTo'),
        set::value($assignedTo),
        set::items($members)
    );
}

/* ====== Define the page structure with zin widgets ====== */

modalHeader();
if(!$canRecordEffort)
{
    if($task->assignedTo != $app->user->account && $task->mode == 'linear')
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, zget($users, $task->assignedTo), $lang->task->start);
    }
    else
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $lang->task->teamMember, $lang->task->start);
    }

    div
    (
        set::className('alert with-icon'),
        icon('exclamation-sign icon-3x'),
        div
        (
            set::className('content'),
            p
            (
                set::className('font-bold'),
                $deniedNotice
            )
        )
    );
}
else
{
    formPanel
    (
        setID('startForm'),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
        formGroup
        (
            set::className($task->mode == 'multi' ? 'hidden' : ''),
            set::width('1/2'),
            set::label($lang->task->assignedTo),
            $assignedToControl
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->task->realStarted),
            datetimePicker
            (
                set::name('realStarted'),
                set::value(helper::isZeroDate($task->realStarted) ? helper::now() : $task->realStarted)
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::label($task->mode == 'linear' ? $lang->task->myConsumed : $lang->task->consumed),
                inputControl
                (
                    input
                    (
                        set::name('consumed'),
                        set::value(!empty($currentTeam) ? helper::formatHours((float)$currentTeam->consumed) : helper::formatHours((float)$task->consumed))
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->task->left),
                inputControl
                (
                    input
                    (
                        set::name('left'),
                        set::value(!empty($currentTeam) ? helper::formatHours((float)$currentTeam->left) : helper::formatHours((float)$task->left))
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            )
        ),
        formGroup
        (
            set::label($lang->comment),
            editor
            (
                set::name('comment'),
                set::rows('5')
            )
        )
    );
    hr();
    history();
}

/* ====== Render page ====== */
render();
