<?php
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      sunguangming <sunguangming@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

modalHeader(set::title($lang->story->batchChangeParent));
formPanel(formGroup
(
    set::label($lang->story->parent),
    picker
    (
        set::name('parent'),
        set::items($parents),
        set::required(true)
    )
));
