<?php
/**
 * The linkStory view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('showGrade', $showGrade);
jsVar('grades', $grades);

$buildModule = $app->rawModule == 'projectrelease' ? 'projectrelease' : 'release';

$cols = array();
foreach($config->release->dtable->defaultFields['linkStory'] as $field) $cols[$field] = zget($config->release->dtable->story->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['link']         = $this->createLink('story', 'view', "storyID={id}");
$cols['title']['nestedToggle'] = false;
$cols['title']['data-size']    = 'lg';

foreach($cols as $colKey => $colConfig) $cols[$colKey]['sort'] = true;

searchForm
(
    set('zui-key', 'searchForm'),
    set::module('story'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, 'story')"))
);

$checkedRows = array_values(array_filter(array_map(function($story){return in_array($story->stage, array('developed', 'closed', 'tested')) ? $story->id : 0;}, $allStories)));
dtable(setID('unlinkStoryList'), set::userMap($users), set::cols($cols), set::data($allStories), set::onRenderCell(jsRaw('window.renderStoryCell')), set::updateCheckedRows($checkedRows), set::loadPartial(true), set::footToolbar(array
(
    'items' => array(array
    (
        'text'         => $lang->release->linkStory,
        'btnType'      => 'primary',
        'className'    => 'size-sm linkObjectBtn',
        'data-type'    => 'story',
        'data-url'     => createLink($buildModule, 'linkStory', "releaseID={$release->id}&browseType={$browseType}&param={$param}"),
        'zui-on-click' => 'handleLinkObjectClick($target)'
    ))
)), set::extraHeight('+144'), set::footer(array('checkbox', 'toolbar', array('html' => html::a(createLink($buildModule, 'view', "releaseID={$release->id}&type=story"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')), set::footPager(usePager()));

render();
