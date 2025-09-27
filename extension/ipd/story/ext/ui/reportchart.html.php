<?php
/**
 * The report view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

h::css(<<<CSS
.module-menu .tree-item > .selected .item-content {color: var(--menu-selected-color) !important; background-color: var(--color-primary-50);}
.module-menu .tree-item .item-content {padding: 4px;}

#mainContainer, #mainContent {min-height: calc(100vh - 3rem);}
CSS
);

$moduleList = array();
foreach($lang->story->report->typeList as $code => $module)
{
    $moduleList[$code] = (object)array(
        'key'    => $code,
        'id'     => $code,
        'name'   => $module,
        'parent' => 0,
        'url'    => createLink('story', 'report', "productID=$productID&branchID=$branchID&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=$code&projectID=$projectID"),
    );
}

detailHeader(to::title
(
    div
    (
        setClass('clip'),
        span
        (
            setClass('font-bold'),
            $lang->story->report->tpl->filter
        ),
        span
        (
            set::title($filterDetail),
            $filterDetail
        )
    )
), hasPriv('report', 'export') ? to::suffix(
    btn
    (
        set(array(
            'type'        => 'primary',
            'text'        => $lang->export,
            'url'         => createLink('story', 'exportchart', array('executionID' => $projectID, 'productID' => $productID, 'browseType' => $browseType, 'moduleID' => $moduleID, 'type' => $chartType)),
            'data-toggle' => 'modal',
            'data-size'   => 'sm'
        ))
    )
) : null);

$staticData->editCanvasConfig->theme            = 'light';
$staticData->editCanvasConfig->previewScaleType = 'top';
$staticDataJson = json_encode($staticData);
div
(
    setClass('flex flex-nowrap'),
    setStyle(array('height' => 'calc(100vh - 8rem)')),
    cell
    (
        set::width('200'),
        moduleMenu
        (
            set::moduleName('story'),
            set::title($lang->story->report->notice),
            set::modules($moduleList),
            set::showDisplay(false),
            set::toggleSidebar(false),
            set::activeKey($chartType),
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
            zget($lang->story->report->typeList, $chartType)
        ),
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
