<?php
namespace zin;

global $lang;

$bug           = data('bug');
$injectionList = data('injectionList');
$identifyList  = data('identifyList');

query('.browserTR')->append(
    h::tr
    (
        h::th
        (
            set::className('py-1.5 pr-2 font-normal nowrap text-right'),
            $lang->bug->injection
        ),
        h::td
        (
            set::className('py-1.5 pl-2 w-full'),
            picker
            (
                set::name('injection'),
                set::items($injectionList),
                set::value($bug->injection)
            )
        )
    ),
    h::tr
    (
        h::th
        (
            set::className('py-1.5 pr-2 font-normal nowrap text-right'),
            $lang->bug->identify
        ),
        h::td
        (
            set::className('py-1.5 pl-2 w-full'),
            picker
            (
                set::name('identify'),
                set::items($identifyList),
                set::value($bug->identify)
            )
        )
    )
);