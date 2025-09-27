<?php
/**
 * The template priv view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($title));
formPanel
(
    formGroup
    (
        set::labelWidth('120px'),
        set::width('full'),
        set::label($lang->project->tplAcl),
        set::name('acl'),
        set::control(array('control' => 'aclBox', 'aclItems' => $lang->project->templateAclList, 'aclValue' => !empty($project->tplAcl) ? $project->tplAcl : 'open', 'whitelistLabel' => $lang->project->whitelist, 'userValue' => !empty($project->tplWhiteList) ? $project->tplWhiteList : ''))
    )
);
