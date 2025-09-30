<?php
$lang->admin->ai->ratingRules       = '评分规则';
$lang->admin->menuList->ai['subMenu']['ratingRules'] = array('link' => "{$lang->admin->ai->ratingRules}|ai|requirementratingrule|", 'alias' => 'storyratingrule,taskratingrule,bugratingrule', 'links' => array('ai|storyratingrule|', 'ai|taskratingrule|', 'ai|bugratingrule'));
$lang->admin->menuList->ai['menuOrder']['25']        = 'ratingRules';
$lang->admin->menuList->ai['dividerMenu']    .= 'ratingRules,';

$lang->admin->ai->promptSetTriggerAction = '触发动作';
if($config->vision != 'or')
{
    $lang->admin->menuList->ai['subMenu']['prompts'] = array('link' => "{$lang->admin->ai->prompt}|ai|prompts|", 'alias' => 'promptview,promptassignrole,promptselectdatasource,promptsetpurpose,promptsettargetform,promptsettriggeraction,promptfinalize,promptedit');
}
