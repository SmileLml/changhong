<?php
/**
 * The preview view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$currentMenu = $currentMenu ?? '';
$generateData = function() use ($lang) {return div(setClass('bg-canvas center text-gray w-full h-40'), $lang->pivot->noPivot);};

$viewFile = strtolower($method) . '.html.php';
if(file_exists($viewFile)) include_once $viewFile;

if($this->config->edition != 'open')
{
    $pivotPath = $this->app->getModuleExtPath('pivot', 'ui');
    include $pivotPath['common'] . 'exportdata.html.php';
}

jsVar('dimensionID', $dimensionID);
jsVar('groupID', $groupID);
jsVar('emptyDrillTip', $this->lang->pivot->emptyDrillTip);

$commonGroups = array_slice($groups, 0, $config->pivot->maxFeatureItem, true);
$moreGroups   = array_slice($groups, $config->pivot->maxFeatureItem, null, true);

foreach($commonGroups as $id => $name) $lang->pivot->featureBar['preview'][$id] = $name;
if(!empty($moreGroups)) $lang->pivot->featureBar['preview']['more'] = $lang->more;
foreach($moreGroups as $id => $name) $lang->pivot->moreSelects['preview']['more'][$id] = $name;

featureBar(set::current($groupID), set::load(''), set::linkParams("dimension={$dimensionID}&group={key}"));

if($config->edition != 'open')
{
    toolbar(hasPriv('pivot', 'export') ? item(set(array
    (
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'ghost',
        'data-target' => '#export',
        'data-toggle' => 'modal',
        'data-size' => 'sm'
    ))) : null, hasPriv('pivot', 'browse') ? item(set(array
    (
        'text'  => $lang->pivot->toDesign,
        'class' => 'primary',
        'url'   => inlink('browse'),
    ))) : null);
}

sidebar
(
    set::width(240),
    moduleMenu
    (
        to::header
        (
            div
            (
                setClass('bg-canvas my-3 mx-5 text-xl font-semibold text-ellipsis h-7 flex-none'),
                $groups[$groupID]
            )
        ),
        set::title($groups[$groupID]),
        set::activeKey($currentMenu),
        set::modules($menus),
        set::closeLink(''),
        set::showDisplay(false),
        set::titleShow(false),
        to::footer
        (
            $this->config->edition == 'open' ? div
            (
                set::width(240),
                setClass('bg-canvas px-4 py-2 module-menu'),
                html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
            ) : null
        )
    )
);
div
(
    setID('pivotContent'),
    setClass('flex col gap-4 w-full'),
    $generateData()
);
