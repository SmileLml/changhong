<?php
/**
 * The instruction view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;
div
(
    set::className('space-y-4'),
    div
    (
        h3($lang->zanode->instructionPage->title),
        div
        (
            set::className('leading-normal'),
            $lang->zanode->instructionPage->desc
        )
    ),
    div
    (
        h5($lang->zanode->instructionPage->imageInstruction),
        img(set::className('w-1/2'), set::src($lang->zanode->instructionPage->image))
    ),
    div(h5($lang->zanode->instructionPage->concept), div(set::className('leading-normal whitespace-pre-line'), $lang->zanode->instructionPage->conceptDesc)),
    div
    (
        h5($lang->zanode->instructionPage->appIntroduction),
        div
        (
            set::className('space-y-6'),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->ZAgentDesc
            ),
            a(set::href($lang->zanode->instructionPage->ZAgentUrl), set::target('_blank'), $lang->zanode->instructionPage->ZAgentUrl),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->ZTFDesc
            ),
            a(set::href($lang->zanode->instructionPage->ZTFUrl), set::target('_blank'), $lang->zanode->instructionPage->ZTFUrl),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->KVMDesc
            ),
            a(set::href($lang->zanode->instructionPage->KVMUrl), set::target('_blank'), $lang->zanode->instructionPage->KVMUrl),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->NginxDesc
            ),
            a(set::href($lang->zanode->instructionPage->NginxUrl), set::target('_blank'), $lang->zanode->instructionPage->NginxUrl),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->noVNCDesc
            ),
            a(set::href($lang->zanode->instructionPage->noVNCUrl), set::target('_blank'), $lang->zanode->instructionPage->noVNCUrl),
            div
            (
                set::className('leading-normal whitespace-pre-line'),
                $lang->zanode->instructionPage->WebsockifyDesc
            ),
            a(set::href($lang->zanode->instructionPage->WebsockifyUrl), set::target('_blank'), $lang->zanode->instructionPage->WebsockifyUrl)
        )
    )
);
