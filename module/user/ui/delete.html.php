<?php
/**
 * The delete view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

h::jsCall('$.getLib', 'md5.js', array('root' => $this->app->getWebRoot() . 'js/'));

modalHeader
(
    set::title(''),
    set::entityID(''),
    set::entityText(sprintf($lang->user->noticeDelete, $user->realname))
);

formPanel
(
    on::click('button[type=submit]', 'encryptPassword'),
    formGroup
    (
        set::label($lang->user->verifyPassword),
        set::control('password'),
        set::name('verifyPassword'),
        set::required(true),
        set::placeholder($lang->user->placeholder->verify)
    ),
    input
    (
        setClass('hidden'),
        set::name('verifyRand'),
        set::value($rand)
    )
);

render();
