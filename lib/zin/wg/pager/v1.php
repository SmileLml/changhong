<?php
namespace zin;

class pager extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'type?: string="full"',
        'page?: int',
        'recTotal?: int',
        'recPerPage?: int',
        'linkCreator?: string',
        'items?: array',
        'sizeMenuCaret?: string'
    );

    /**
     * @param string $type
     */
    protected function buildProps($type = 'full')
    {
        global $lang;
        $pager = data('pager');
        $pager->setParams();
        $params = $pager->params;
        foreach($params as $key => $value)
        {
            if(strtolower($key) === 'recperpage') $params[$key] = '{recPerPage}';
            if(strtolower($key) === 'pageid')     $params[$key] = '{page}';
        }

        $props = array();
        $props['page']        = $pager->pageID;
        $props['recTotal']    = $pager->recTotal;
        $props['recPerPage']  = $pager->recPerPage;
        $props['linkCreator'] = createLink($pager->moduleName, $pager->methodName, $params);

        $items = $this->prop('items');
        if(!$items) $items = array
        (
            $type == 'short' ? null : array('type' => 'info', 'text' => $lang->pager->totalCountAB),
            $type == 'short' ? null : array('type' => 'size-menu', 'text' => $lang->pager->pageSizeAB),
            array('type' => 'link', 'hint' => $lang->pager->firstPage, 'page' => 'first', 'icon' => 'icon-first-page'),
            array('type' => 'link', 'hint' => $lang->pager->previousPage, 'page' => 'prev', 'icon' => 'icon-angle-left'),
            array('type' => 'info', 'text' => '{page}/{pageTotal}'),
            array('type' => 'link', 'hint' => $lang->pager->nextPage, 'page' => 'next', 'icon' => 'icon-angle-right'),
            array('type' => 'link', 'hint' => $lang->pager->lastPage, 'page' => 'last', 'icon' => 'icon-last-page')
        );
        foreach($items as &$item)
        {
            if($item['type'] !== 'size-menu' || isset($item['caret'])) continue;
            $item['caret'] = $this->prop('sizeMenuCaret');
        }
        $props['items'] = $items;

        $this->setProp($props);
    }

    protected function build()
    {
        $this->buildProps($this->prop('type'));

        return zui::pager(inherit($this));
    }
}
