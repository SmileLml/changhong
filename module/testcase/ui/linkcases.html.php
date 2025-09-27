<?php
/**
 * The link cases view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->testcase->linkCases),
    set::entityText($case->title),
    set::entityID($case->id)
);

searchForm
(
    set::module('testcase'),
    set::simple(true),
    set::show(true)
);

$footToolbar = array('items' => array(array('text' => $lang->save, 'btnType' => 'secondary', 'className' => 'link-btn')));

dtable
(
    set::cols($config->testcase->linkcases->dtable->fieldList),
    set::data($cases2Link),
    set::userMap($users),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render('modalDialog');
