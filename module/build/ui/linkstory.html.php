<?php
/**
 * The linkStory view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

$buildModule = $app->tab == 'project' ? 'projectbuild' : 'build';
$cols        = array();
foreach($config->build->defaultFields['linkStory'] as $field) $cols[$field] = zget($config->story->dtable->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['link']         = $this->createLink('story', 'view', "storyID={id}&version=0&param={objectID}");
$cols['title']['nestedToggle'] = false;
$cols['title']['data-toggle']  = 'modal';
$cols['title']['data-size']    = 'lg';
$cols['assignedTo']['type']    = 'user';

foreach($allStories as $story)
{
    $story->objectID = $this->app->tab == 'execution' ? $build->execution : $build->project;
    $story->estimate = $story->estimate . $config->hourUnit;
}

jsVar('childrenAB', $lang->story->childrenAB);
jsVar('grades', $grades);
jsVar('showGrade', $showGrade);

searchForm
(
    set('zui-key', 'searchForm'),
    set::module('story'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, 'story')"))
);

dtable
(
    setID('unlinkStoryList'),
    set::userMap($users),
    set::cols($cols),
    set::orderBy($orderBy),
    set::sortLink(createLink($buildModule, 'linkStory', "buildID={$build->id}&browseType=$browseType&param=$param&orderBy={name}_{sortType}")),
    set::data(array_values($allStories)),
    set::loadPartial(true),
    set::extraHeight('+144'),
    set::onRenderCell(jsRaw('window.renderStoryCell')),
    set::checkedRows($checkedRows),
    set::footToolbar(array('items' => array(array
    (
        'text'         => $lang->productplan->linkStory,
        'btnType'      => 'primary',
        'className'    => 'size-sm linkObjectBtn',
        'data-type'    => 'linkStory',
        'data-url'     => createLink($buildModule, 'linkStory', "buildID={$build->id}&browseType=$browseType&param=$param"),
        'zui-on-click' => 'handleLinkObjectClick($target)'
    )))),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(helper::createLink($buildModule, 'view', "buildID=$build->id&type=story"). "#app={$app->tab}", $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);
