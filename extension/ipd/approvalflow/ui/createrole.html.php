<?php
/**
 * The create role view file of approval flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     approvalflow
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('approvalflow.createRole');

formPanel
(
    set::title($lang->approvalflow->createRole),
    set::modeSwitcher(false),
    set::fields($fields),
    set::width('100%')
);
