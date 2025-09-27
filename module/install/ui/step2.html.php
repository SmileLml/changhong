<?php
/**
 * The step2 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

include $this->app->getConfigRoot() . 'timezones.php';

set::zui(true);

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        panel
        (
            setClass('py-2'),
            set::title($lang->install->setConfig),
            form
            (
                set::submitBtnText($lang->install->next),
                set::actions(array('submit')),
                h::table
                (
                    setClass('table bordered'),
                    h::tr
                    (
                        h::th(setClass('text-right w-1/5'), $lang->install->key),
                        h::th($lang->install->value),
                        h::th()
                    ),
                    h::tr
                    (
                        h::th(setClass('text-right'), $lang->install->timezone),
                        h::td
                        (
                            picker
                            (
                                set::name('timezone'),
                                set::items($timezoneList),
                                set::value($config->timezone),
                                set::required(true)
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th(setClass('text-right'), $lang->install->defaultLang),
                        h::td
                        (
                            picker
                            (
                                set::name('defaultLang'),
                                set::items($config->langs),
                                set::value($app->getClientLang()),
                                set::required(true)
                            )
                        ),
                        h::td()
                    ),
                    $config->edition != 'open' ? h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbDriver),
                        h::td
                        (
                            picker
                            (
                                set::name('dbDriver'),
                                set::items($lang->install->dbDriverList),
                                set::value('mysql'),
                                set::required(true)
                            )
                        ),
                        h::td()
                    ) : input
                    (
                        setClass('hidden'),
                        set::name('dbDriver'),
                        set::value('mysql')
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbHost),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbHost'),
                                    set::value($dbHost)
                                )
                            )
                        ),
                        h::td($lang->install->dbHostNote)
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbPort),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbPort'),
                                    set::value($dbPort)
                                )
                            )
                        )
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbEncoding),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbEncoding'),
                                    set::value($this->config->db->encoding)
                                )
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbUser),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbUser'),
                                    set::value($dbUser)
                                )
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        $config->inQuickon ? setClass('hidden') : null,
                        h::th(setClass('text-right'), $lang->install->dbPassword),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbPassword'),
                                    set::value($dbPassword)
                                )
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th(setClass('text-right'), $lang->install->dbName),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbName'),
                                    set::value($dbName)
                                )
                            )
                        ),
                        h::td()
                    ),
                    h::tr
                    (
                        h::th(setClass('text-right'), $lang->install->dbPrefix),
                        h::td
                        (
                            formGroup
                            (
                                input
                                (
                                    set::name('dbPrefix'),
                                    set::value('zt_')
                                )
                            )
                        ),
                        h::td
                        (
                            checkbox
                            (
                                set::text($lang->install->clearDB),
                                set::name('clearDB'),
                                set::value(1)
                            )
                        )
                    )
                ),
                contactUs()
            )
        )
    )
);

render('pagebase');
