<?php
/**
 * The report view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$moduleList = array();
foreach($lang->task->report->typeList as $code => $module)
{
    $moduleList[$code] = (object)array(
        'key' => $code,
        'id'  => $code,
        'name' => $module,
        'parent' => 0,
        'url'    => createLink('task', 'report', "executionID=$executionID&browseType=$browseType&param=$param&type=$code")
    );
}

jsVar('executionID', $executionID);
jsVar('browseType', $browseType);

detailHeader
(
    set::backUrl(createLink('execution', 'task', "executionID=$executionID") . "#app={$app->tab}"),
    to::title
    (
        div
        (
            setClass('clip'),
            span
            (
                setClass('font-bold'),
                $lang->task->report->tpl->filter
            ),
            span
            (
                set::title($filterDetail),
                $filterDetail
            )
        )
    )
);

$staticData->editCanvasConfig->theme            = 'light';
$staticData->editCanvasConfig->previewScaleType = 'top';
$staticDataJson = json_encode($staticData);
div
(
    setClass('flex flex-nowrap'),
    setStyle(array('height' => 'calc(100vh - 10rem)')),
    cell
    (
        set::width('200'),
        moduleMenu
        (
            set::moduleName('task'),
            set::title($lang->task->report->notice),
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
        h4
        (
            setClass('mt-2 ml-1'),
            zget($lang->task->report->typeList, $type)
        ),
        h::iframe
        (
            setID('staticScreen'),
            set('width', '100%'),
            set('height', '100%'),
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
