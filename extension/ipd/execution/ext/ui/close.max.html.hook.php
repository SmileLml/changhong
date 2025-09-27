<?php
namespace zin;

$execution = data('execution');
if(helper::hasFeature('deliverable') && empty($execution->isTpl))
{
    global $lang;
    $project      = data('project');
    $deliverables = data('deliverables');
    if($project->model != 'ipd' && $project->model != 'kanban' && $execution->status == 'doing' && $execution->grade == 1)
    {
        /* 追加交付物组件。 */
        $deliverable = formGroup
        (
            set::label($lang->project->deliverableAbbr),
            deliverable(set::items($deliverables))
        );
        query('formPanel')->append($deliverable);
    }
}
