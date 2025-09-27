<?php
namespace zin;

class detailSide extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'isForm?: bool=false'
    );

    public static function getPageCSS()
    {
        return <<<CSS
        .detail-side {width: 370px;}
        .detail-side .tab-content>.tab-pane {padding-left: 0 !important;}
        .detail-side .tabs:not(:first-child) {border-top: 1px solid #E6EAF1;}
        .detail-side .tabs {padding-top: 12px; padding-bottom: 20px;}
        .detail-side > .table-data {width: 100%;}
CSS;
    }

    protected function buildExtraSide()
    {
        global $app, $lang;

        $app->control->loadModel('flow');
        $isForm    = $this->prop('isForm');
        $object    = data($app->getModuleName());
        $fields    = $app->control->appendExtendForm('basic', $object);
        $extraSide = array();
        foreach($fields as $field)
        {
            $extraSide[] = item
            (
                $field->control == 'file' && $object->files ? fileList
                (
                    set::files($object->files),
                    set::extra($field->field),
                    set::fieldset(false),
                    set::showEdit(true),
                    set::showDelete(true)
                ) : null,
                set::name($field->name),
                !$isForm ? div($app->control->flow->getFieldValue($field, $object)) : formGroup
                (
                    set::id($field->field),
                    set::name($field->field),
                    set::required($field->required),
                    set::disabled((bool)$field->readonly),
                    set::control($field->control),
                    set::items($field->items),
                    set::value($field->value)
                )
            );
        }
        return $extraSide ? tableData(set::title($lang->extInfo), $extraSide) : null;
    }

    protected function build()
    {
        $extraSide = $this->buildExtraSide();
        return div
        (
            setClass('detail-side canvas flex-none px-6 h-min'),
            set($this->getRestProps()),
            $this->children(),
            $extraSide
        );
    }
}
