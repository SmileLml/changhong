<?php
namespace zin;

$model      = $project->model;
$labelClass = $config->project->labelClass[$model];

$fields = useFields('project.edittemplate');

jsVar('currencySymbol', $lang->project->currencySymbol);

formGridPanel
(
    to::titleSuffix
    (
        btn
        (
            set::id('project-model'),
            setClass("{$labelClass} h-5 px-2"),
            zget($lang->project->modelList, $model, '')
        )
    ),
    formHidden('storyType[]', 'story'),
    formHidden('model', $model),
    on::change('[name=future]', 'toggleBudget'),
    set::modeSwitcher(false),
    set::defaultMode('full'),
    set::title($lang->project->editTemplate),
    set::fields($fields)
);

render();
