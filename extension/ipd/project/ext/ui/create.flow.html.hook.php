<?php
namespace zin;

$workflowGroupPairs = data('workflowGroupPairs');
$copyProject        = data('copyProject');
$copyType           = data('copyType');
$pageType           = data('pageType');
$project            = data('project');

$workflowGroup = 0;
if(isset($copyProject->workflowGroup) && isset($workflowGroupPairs[$copyProject->workflowGroup]))
{
    $workflowGroup = $copyProject->workflowGroup;
}
elseif(isset($project->workflowGroup))
{
    $workflowGroup = $project->workflowGroup;;
}

$loadUrl            = helper::createLink('project', 'create', 'model=' . data('model') . '&program={parent}&copyProjectID=' . data('copyProjectID') . '&extra=workflowGroup={workflowGroup},charter={charter},hasProduct={hasProduct}' . (!empty($copyType) ? ",copyType={$copyType}" : '') . '&pageType=' . $pageType);
query('formGridPanel')->each(function($node) use($workflowGroup, $loadUrl)
{
    $lang              = data('lang');
    $model             = data('model');
    $workflowGroups    = data('workflowGroups');
    $copyWorkflowGroup = data('copyWorkflowGroup');
    $hasProductValue   = data('hasProduct') === null ? (data('copyProjectID') ? data('copyProject.hasProduct') : '1') : data('hasProduct');
    $fields = $node->prop('fields');

    if($model != 'kanban')
    {
        $fields->field('charter')
            ->class('charterBox')
            ->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'changeCharter'))
            ->items(data('charters'))
            ->value(data('charter'))
            ->hidden(data('config.systemMode') == 'light')
            ->moveAfter('parent');

        $fields->field('linkType')->value(data('linkType'))->hidden(true);

        $fields->field('productsBox')
            ->width('full')
            ->required(data('copyProject.parent') || data('parentProgram.id') || data('project.parent'))
            ->control(array
            (
                'control'           => 'productsBox',
                'productItems'      => data('charter') ? data('charterProductPairs') : data('allProducts'),
                'branchGroups'      => data('charter') ? data('branchPairs') : data('branchGroups'),
                'planGroups'        => data('charter') ? data('charterPlans') : data('productPlans'),
                'roadmapGroups'     => data('productRoadmaps'),
                'productPlans'      => data('productPlans'),
                'linkedProducts'    => data('charter') ? data('charterProducts') : data('linkedProducts'),
                'linkedBranches'    => data('linkedBranches'),
                'project'           => data('project') ? data('project') : data('copyProject'),
                'hasNewProduct'     => data('app.rawMethod') == 'create',
                'isStage'           => data('isStage'),
                'type'              => data('linkType'),
                'errorSameProducts' => $lang->project->errorSameProducts,
                'selectTip'         => $lang->project->selectProductTip,
                'hidden'            => !$hasProductValue && !data('charter')
            ));

        if(!empty($copyWorkflowGroup) && $copyWorkflowGroup->objectID)
        {
            $fields->field('workflowGroup')
                ->label($lang->project->workflowGroup)
                ->control('picker')
                ->required(true)
                ->readonly(true)
                ->value($copyWorkflowGroup->id)
                ->items(array($copyWorkflowGroup->id => $copyWorkflowGroup->name));
        }
        else
        {
            $fields->field('workflowGroup')
                ->label($lang->project->workflowGroup)
                ->control('picker')
                ->required(true)
                ->value($workflowGroup)
                ->items($workflowGroups);
        }

        $fields->field('workflowGroup')->width('1/2');
        $fields->field('hasProduct')->width('1/2');

        $fields->moveAfter('workflowGroup', 'hasProduct');

        $fields->autoLoad('workflowGroup')
            ->autoLoad('parent',  'charter,acl,productsBox,hasProduct,linkType')
            ->autoLoad('charter', 'productsBox,linkType');

    }

    $fields->autoLoad('hasProduct', 'workflowGroup');

    $fields->fullModeOrders('parent,charter,category,hasProduct,workflowGroup');
    $fields->orders('parent,charter,category,hasProduct,workflowGroup');

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
