<?php
/**
 * The report view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@chandao.com>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$moduleList = array();
foreach($lang->project->reportSettings->typeList[$browseType] as $code => $module)
{
    $moduleList[$code] = (object)array(
        'key'    => $code,
        'id'     => $code,
        'name'   => $module,
        'parent' => 0,
        'url'    => createLink('project', "report$browseType", "projectID=$projectID&type=$code")
    );
}

detailHeader
(
    to::prefix
    (
        btn
        (
            set::icon('back'),
            set::type('primary-outline'),
            set::url(createLink('project', $browseType, "projectID=$projectID") . "#app={$app->tab}"),
            $lang->goback
        )
    ),
    to::title
    (
        div
        (
            setClass('clip'),
            span
            (
                setClass('text-base'),
                sprintf($lang->project->reportSettings->subtitle, $lang->bug->common)
            )
        )
    ),
    hasPriv('report', 'export') ? to::suffix
    (
        btn
        (
            set::type('primary'),
            set::text($lang->export),
            set::url(createLink('project', 'exportchart', array('projectID' => $projectID, 'type' => $type, 'from' => 'bug'))),
            setData('toggle', 'modal'),
            setData('size', 'sm')
        )
    ) : null
);

$staticData->editCanvasConfig->theme            = 'light';
$staticData->editCanvasConfig->previewScaleType = 'top';
$staticDataJson = json_encode($staticData);
div
(
    setClass('flex flex-nowrap report-view'),
    setStyle(array('height' => 'calc(100vh - 8rem)')),
    cell
    (
        set::width('200'),
        moduleMenu
        (
            set::moduleName('project'),
            set::title($lang->project->reportSettings->notice),
            set::modules($moduleList),
            set::showDisplay(false),
            set::toggleSidebar(false),
            set::activeKey($type),
            set::closeLink('')
        )
    ),
    cell
    (
        setID('chartContainer'),
        set::flex('1'),
        setClass('ml-4 bg-white px-2 py-2'),
        h4(setClass('mt-2 ml-1'), zget($lang->execution->report->typeList[$browseType], $type)),
        h::iframe
        (
            setID('staticScreen'),
            set('width', '100%'),
            set('height', '95%'),
            set('scrolling', 'no'),
            set('frameborder', '0'),
            set('marginheight', '0'),
            set('src', createLink('screen', 'staticDataOld')),
            on::init()->do(<<<JS
    const iframe = document.getElementById('staticScreen');
    iframe.onload = function()
    {
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        iframe.contentWindow.setStaticData('{$staticDataJson}', {width: iframe.offsetWidth});
        const style = document.createElement('style');
        style.textContent = 'body {background-color: #fff !important;}';
        iframeDoc.head.appendChild(style);
    };
JS
)
        )
    )
);
