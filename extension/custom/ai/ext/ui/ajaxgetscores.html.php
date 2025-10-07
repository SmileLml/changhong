<?php

namespace zin;

formPanel
(
    set::title($lang->ai->score->common),
    set::actions(array()),
    dtable
    (
        set::cols($config->aiscore->dtable->fieldList),
        set::data(array_values($aiScores)),
        set::emptyTip($lang->aiscore->noScore)
    )
);

render();
