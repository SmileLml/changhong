<?php
/**
 * The view view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('nodeID', $zanode->id);
jsVar('zanodeLang', $lang->zanode);
jsVar('nodeStatus', $zanode->status);
jsVar('hostType', $zanode->hostType);
jsVar('webRoot', getWebRoot());

$account = strpos(strtolower($zanode->osName), "windows") !== false ? $config->zanode->defaultWinAccount : $config->zanode->defaultAccount;
$ssh     = $zanode->hostType == 'physics' ? ('ssh ' . $zanode->extranet) : ($zanode->ssh ? 'ssh ' . $account . '@' . $zanode->ip . ' -p ' . $zanode->ssh : '');

$snapshotUrl = helper::createLink('zanode', 'browseSnapshot', "nodeID={$zanode->id}");

$mainActions   = array();
$commonActions = array();
foreach($config->zanode->view->operateList as $operate)
{
    if(strpos($operate, '|') !== false)
    {
        $operates = explode('|', $operate);
        foreach($operates as $operate)
        {
            if($this->zanode->isClickable($zanode, $operate)) break;
        }
    }

    if(!common::hasPriv('zanode', $operate)) continue;
    $action = $config->zanode->dtable->fieldList['actions']['list'][$operate];
    if(!$this->zanode->isClickable($zanode, $operate))
    {
        unset($action);
        continue;
    }

    if($operate === 'edit' || $operate === 'destroy')
    {
        $commonActions[] = $action;
        continue;
    }
    $mainActions[] = $action;
}

$baseInfo = array();
if($zanode->hostType == 'physics')
{
    $baseInfo[] = div
    (
        setClass('w-1/3'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->osName
        ),
        span
        (
            setClass('ml-2'),
            zget($config->zanode->linuxList, $zanode->osName, zget($config->zanode->windowsList, $zanode->osName))
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/2'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->sshAddress
        ),
        span
        (
            setClass('ml-2'),
            $ssh,
            $ssh ? h::button
            (
                setClass('ghost btn btn-info size-sm btn-ssh-copy'),
                icon
                (
                    setClass('icon-common-copy icon-copy'),
                    set::title($lang->zanode->copy),
                    set::name('')
                ),
                on::click('sshCopy')
            ) : null,
            h::input
            (
                set::type('hidden'),
                setID('ssh-copy'),
                set::value($ssh)
            )
        )
    );
    $hiddenHost || $zanode->cpuCores == 0 ? null : $baseInfo[] = div
    (
        setClass('w-1/6'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->cpuCores
        ),
        span
        (
            setClass('ml-2'),
            $zanode->cpuCores . ' ' . $lang->zanode->cpuUnit
        )
    );
    $baseInfo[] = div
    (
        $hiddenHost || $zanode->cpuCores == 0 ? setClass('w-1/6') : setClass('w-1/3 mt-2'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->status
        ),
        span
        (
            setClass('ml-2'),
            zget($lang->zanode->statusList, $zanode->status)
        )
    );
    $hiddenHost || $zanode->memory == 0 ? null : $baseInfo[] = div
    (
        setClass('w-1/2 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->memory
        ),
        span
        (
            setClass('ml-2'),
            $zanode->memory . 'GB'
        )
    );
    $hiddenHost || $zanode->diskSize == 0 ? null : $baseInfo[] = div
    (
        setClass('w-1/6 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->diskSize
        ),
        span
        (
            setClass('ml-2'),
            $zanode->diskSize . 'GB'
        )
    );
}
else
{
    $baseInfo[] = div
    (
        setClass('w-1/3'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->osName
        ),
        span
        (
            setClass('ml-2'),
            $zanode->osName
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/2'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->sshCommand
        ),
        span
        (
            setClass('ml-2'),
            $ssh,
            $ssh ? h::button
            (
                setClass('ghost btn btn-info size-sm btn-ssh-copy'),
                icon
                (
                    setClass('icon-common-copy icon-copy'),
                    set::title($lang->zanode->copy),
                    set::name('')
                ),
                on::click('sshCopy')
            ) : null,
            h::textarea
            (
                setClass('hidden'),
                setID('ssh-copy'),
                $ssh
            )
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/6'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->cpuCores
        ),
        span
        (
            setClass('ml-2'),
            $zanode->cpuCores . '' . $lang->zanode->cpuUnit
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/3 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->status
        ),
        span
        (
            setClass('ml-2'),
            zget($lang->zanode->statusList, $zanode->status)
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/2 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->defaultUser
        ),
        span
        (
            setClass('ml-2'),
            $account
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/6 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->memory
        ),
        span
        (
            setClass('ml-2'),
            $zanode->memory . ' GB'
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/3 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->hostName
        ),
        span
        (
            setClass('ml-2 clip w-36 inline-flex'),
            set::title($zanode->hostName),
            $zanode->hostName
        )
    );
    $baseInfo[] = div
    (
        setClass('w-1/2 mt-4'),
        span
        (
            setClass('text-gray'),
            $lang->zanode->defaultPwd
        ),
        span
        (
            setClass('ml-2'),
            span
            (
                setID('pwd-text'),
                str_repeat('*', strlen($config->zanode->defaultPwd))
            ),
            h::button
            (
                setClass('ghost btn btn-info size-sm btn-pwd-copy'),
                icon
                (
                    setClass('icon-common-copy icon-copy'),
                    set::title($lang->zanode->copy),
                    set::name('')
                ),
                on::click('pwdCopy')
            ),
            h::button
            (
                setClass('ghost btn btn-info size-sm btn-pwd-show'),
                icon
                (
                    setClass('icon-common-eye icon-eye'),
                    set::title($lang->zanode->showPwd),
                    set::name('')
                ),
                on::click('pwdShow')
            ),
            h::input
            (
                setID('pwd-copy'),
                set::type('hidden'),
                set::value($config->zanode->defaultPwd)
            )
        )
    );
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($zanode->id),
            set::level(1),
            set::text($zanode->name)
        ),
        $zanode->deleted ? span(setClass('label danger'), $lang->zanode->deleted) : null
    )
);

detailBody
(
    sectionList
    (
        setID('baseInfoContent'),
        section
        (
            set::title($lang->zanode->baseInfo),
            h::table
            (
                setClass('w-full'),
                h::tr
                (
                    h::td
                    (
                        div
                        (
                            setClass('flex flex-wrap pt-2 mx-4'),
                            $baseInfo
                        )
                    )
                )
            )
        ),
        section
        (
            set::title($lang->zanode->desc),
            !empty($zanode->desc) ? html(htmlspecialchars_decode($zanode->desc)) : $lang->noData
        )
    ),
    sectionList
    (
        setID('serviceContent'),
        div
        (
            setClass('text-lg font-bold'),
            $lang->zanode->init->statusTitle,
            button
            (
                setClass('ghost btn'),
                icon('refresh', setClass('text-primary')),
                $lang->zanode->init->checkStatus,
                on::click()->call('window.checkServiceStatus')
            )
        ),
        div
        (
            setClass('service-status hidden'),
            span
            (
                setClass('dot-symbol dot-zenagent text-danger'),
                '●'
            ),
            span
            (
                ' ZAgent ',
                span
                (
                    setClass('zenagent-status'),
                    $lang->zanode->initializing
                )
            )
        ),
        div
        (
            setClass('service-status hidden'),
            span
            (
                setClass('dot-symbol dot-ztf text-danger'),
                '●'
            ),
            span
            (
                ' ZTF ',
                span
                (
                    setClass('ztf-status'),
                    $lang->zanode->initializing
                )
            )
        ),
        div
        (
            setID('statusContainer')
        ),
        div
        (
            setClass('status-notice'),
            span
            (
                setClass('init-success hidden'),
                html(sprintf($lang->zanode->init->initSuccessNoticeTitle, "<a href='https://www.zentao.net/book/zentaopms/974.html?fullScreen=zentao'>{$lang->zanode->manual}</a>", html::a(createLink('testcase', 'automation'), $lang->zanode->automation, '', "data-toggle='modal'")))
            ),
            $zanode->hostType == 'physics' ? div
            (
                setClass('init-fail hidden'),
                html($lang->zanode->init->initFailNoticeOnPhysics),
                textarea
                (
                    setID('initBash'),
                    setClass('hidden'),
                    $initBash
                ),
                div
                (
                    setClass('zanode-init'),
                    $initBash,
                    h::button
                    (
                        setClass('ghost btn btn-info size-sm btn-init-copy text-primary'),
                        icon
                        (
                            setClass('icon-common-copy icon-copy'),
                            set::title($lang->zanode->copy),
                            set::name('')
                        ),
                        on::click('onCopy')
                    )
                )
            ) : null
        )
    ),
    common::hasPriv('zanode', 'browseSnapshot') && $zanode->hostType == '' ? sectionList
    (
        section
        (
            set::title($lang->zanode->browseSnapshot),
            to::actions
            (
                div
                (
                    setClass('ml-auto'),
                    btn
                    (
                        setClass('btn primary'),
                        $lang->zanode->createSnapshot,
                        set::url(createLink('zanode', 'createSnapshot', "zanodeID={$zanode->id}")),
                        set::icon('plus'),
                        setData(array('toggle' => 'modal')),
                        set::disabled($zanode->status != 'running' ? true : false)
                    )
                )
            )
        ),
        !empty($snapshotList) ? div(setID('snapshotList'), on::init()->do("$('#snapshotList').load('{$snapshotUrl}');")) : $lang->noData
    ) : null,
    floatToolbar
    (
        to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($mainActions),
        set::suffix($commonActions),
        set::object($zanode)
    ),
    detailSide
    (
        history()
    )
);

render();
