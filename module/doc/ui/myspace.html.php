<?php
/**
 * The mySpace view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
include 'lefttree.html.php';
if($libID && common::hasPriv('doc', 'create')) include 'createbutton.html.php';

jsVar('browseType', $browseType);
jsVar('docLang', $lang->doc);
jsVar('confirmDelete', $lang->doc->confirmDelete);
jsVar('appTab', $app->tab);

/* zin: Define the set::module('doc') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("type={$type}&libID={$libID}&moduleID={$moduleID}&browseType={key}"),
    li(searchToggle(set::module($type . $libType . 'Doc')))
);
toolbar
(
    $canExport ? item(set(array
    (
        'icon'        => 'export',
        'class'       => 'ghost export',
        'id'          => 'mine2export',
        'text'        => $lang->export,
        'url'         => createLink('doc', 'mine2export', "libID={$libID}&moduleID={$moduleID}"),
        'data-size'   => 'sm',
        'data-toggle' => 'modal'
    ))) : null,
    common::hasPriv('doc', 'createLib') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn secondary',
        'text'        => $lang->doc->createLib,
        'url'         => createLink('doc', 'createLib', 'type=mine'),
        'data-toggle' => 'modal',
        'data-size'   => '500'
    ))) : null,
    $libID && common::hasPriv('doc', 'create') ? $createButton : null
);

include 'mydoclist.html.php';
$docContent;
