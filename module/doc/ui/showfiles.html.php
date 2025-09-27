<?php
/**
 * The showfiles view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('imageExtensionList', $config->file->imageExtensions);
jsVar('sessionString', session_name() . '=' . session_id());
jsVar('+searchLink', createLink('doc', 'showFiles', "type={$type}&objectID={$objectID}&viewType={$viewType}&browseType={$browseType}&param={$param}&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&searchTitle=%s"));

$filesBody = null;
$canExport = $config->edition != 'open' && common::hasPriv('doc', 'exportFiles');
$linkTpl = array('linkCreator' => helper::createLink('doc', $app->rawMethod, "type={$type}&objectID={$objectID}&viewType={$viewType}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&searchTitle={$searchTitle}"));
if($viewType == 'list')
{
    $fieldList = $config->doc->showfiles->dtable->fieldList;
    if($canExport) $fieldList['id']['type'] = 'checkID';

    $tableData = initTableData($files, $fieldList);
    $filesBody = dtable
    (
        set::checkable($canExport),
        set::userMap($users),
        set::cols($fieldList),
        set::data($tableData),
        set::emptyTip($lang->pager->noRecord),
        set::sortLink(inlink('showFiles', "type={$type}&objectID={$objectID}&viewType={$viewType}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle={$searchTitle}")),
        set::orderBy($orderBy),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager($linkTpl))
    );
}
elseif($files)
{
    $cardsBox = null;
    foreach($files as $file)
    {
        $url  = helper::createLink('file', 'download', "fileID={$file->id}");
        $url .= strpos($url, '?') === false ? '?' : '&';
        $url .= session_name() . '=' . session_id();

        $downloadLink = $this->createLink('file', 'download', "fileID={$file->id}&mouse=left");
        $cardsBox[] = div
         (
             setClass('col'),
             div
             (
                 setClass('lib-file'),
                 div
                 (
                     setClass('file'),
                     a
                     (
                         set::href($url),
                         set::title($file->title),
                         set::target('_blank'),
                         in_array($file->extension, array('jpg', 'jpeg', 'gif', 'png', 'bmp')) ? set('onclick', "return downloadFile({$file->id}, '{$file->extension}', {$file->imageWidth})") : null,
                         in_array($file->extension, $config->file->imageExtensions) ? div
                         (
                             setClass('img-holder'),
                             set('style', "background-image: url({$file->webPath})"),
                             img(setClass(empty($file->imageWidth) ? 'not-exist' : ''), set('src', $file->webPath))
                         ) : html($file->fileIcon)
                     ),
                     div(setClass('file-name'), set::title($file->title), $file->title),
                     div
                     (
                         setClass('file-name text-gray'),
                         $file->objectName,
                         a
                         (
                             set::href(createLink(($file->objectType == 'requirement' ? 'story' : $file->objectType), 'view', "objectID={$file->objectID}")),
                             set::title($file->sourceName),
                             $file->sourceName,
                             $file->objectType != 'doc' ? set(array('data-toggle' => 'modal', 'data-size' => 'lg')) : null
                         )
                     )
                 )
             )
         );
    }

    $filesBody = panel
    (
        setClass('block-files'),
        div
        (
            setClass('row row-grid files-grid'),
            set('data-size', 300),
            $cardsBox
        ),
        pager(set::_className('flex justify-end items-center'), set(usePager($linkTpl)))
    );
}
else
{
    $filesBody = div
    (
        setClass('table-empty-tip shadow ring rounded flex justify-center items-center'),
        setStyle(array('min-height' => '212px')),
        span(setClass('text-gray'), $lang->pager->noRecord)
    );
}

include 'lefttree.html.php';
featureBar
(
    li(searchToggle(set::module($type . 'DocFile'), set::open($browseType == 'bySearch')))
);

toolbar
(
    div
    (
        setClass('flex'),
        $canExport ? btn
        (
            setClass('ghost export mr-2'),
            set::icon('export'),
            set::text($lang->export),
            set::url(createLink('doc', 'exportFiles', "objectID={$objectID}&objectType={$type}")),
            setData('size', 'sm'),
            setData('toggle', 'modal')
        ) : null,
        div
        (
            setClass('btn-group'),
            a
            (
                icon('bars'),
                setClass('btn switchBtn'),
                setClass($viewType == 'list' ? ' text-primary' : ''),
                set::href(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list&browseType={$browseType}&param={$param}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle={$searchTitle}")),
                set('data-app', $app->tab)
            ),
            a
            (
                icon('cards-view'),
                setClass('btn switchBtn'),
                setClass($viewType != 'list' ? ' text-primary' : ''),
                set::href(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card&browseType={$browseType}&param={$param}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle=$searchTitle")),
                set('data-app', $app->tab)
            )
        ),
        common::hasPriv('doc', 'createLib') ? btn
        (
            setClass('ml-4 btn secondary'),
            set::text($lang->doc->createLib),
            set::icon('plus'),
            set::url(createLink('doc', 'createLib', "type={$type}&objectID={$objectID}")),
            setData('toggle', 'modal'),
            setData('size', 'sm')
        ) : null
    )
);

div
(
    div(setClass('mt-2'), $filesBody)
);
/* ====== Render page ====== */
render();
