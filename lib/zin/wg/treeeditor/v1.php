<?php
namespace zin;

class treeEditor extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'items: array',
        'type?: string',
        'id?: string',
        'icon?: string',
        'class?: string',
        'sortable?: array',
        'itemProps?: array',
        'onSort?: function',
        'canSortTo?: function',
        'selected?: string',
        'canUpdateOrder?: bool=false',
        'canEdit?: bool=false',
        'canDelete?: bool=false',
        'canSplit?: bool=true',
        'checkbox?: bool=false',
        'checkOnClick?: bool=false',
        'preserve?: bool=true'
    );

    protected function build()
    {
        $this->setProp('items', $this->buildTree($this->prop('items')));
        $treeProps = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'collapsedIcon', 'expandedIcon', 'normalIcon', 'itemActions', 'hover', 'onClick', 'sortable', 'itemProps', 'onSort', 'canSortTo', 'checkbox', 'checkOnClick', 'preserve'));
        $id = $this->prop('id');

        if(empty($id))
        {
            global $app;
            $id = "treeEditor-{$app->rawModule}-{$app->rawMethod}";
        }

        $treeType = (!empty($treeProps['onSort']) || !empty($treeProps['sortable'])) ? 'sortableTree' : 'tree';
        return div
        (
            setStyle('--menu-selected-bg', 'none'),
            zui::$treeType
            (
                set::_id($id),
                set::_tag('menu'),
                set::lines(),
                set::preserve($id),
                set($treeProps)
            )
        );
    }

    /**
     * @param mixed[] $items
     */
    private function buildTree($items)
    {
        global $app;

        $canEdit   = $this->prop('canEdit');
        $canDelete = $this->prop('canDelete');
        $canSplit  = $this->prop('canSplit');
        $editType  = $this->prop('type');
        $selected  = $this->prop('selected');
        $typeList  = array('task' => 'T', 'bug' => 'B', 'case' => 'C', 'feedback' => 'F', 'ticket' => 'T');
        $viewType  = data('viewType') ? data('viewType') : '';
        $sortTree  = $this->prop('sortable') || $this->prop('onSort');

        foreach($items as $key => $item)
        {
            $item         = (array)$item;
            $itemCanSplit = isset($item['canSplit']) ? $item['canSplit'] : $canSplit;
            if(!isset($item['content']))
            {
                if(!isset($item['text'])) $item['text'] = $item['name'];
                if(!isset($item['url']))  $item['url']  = '';

                $item['titleAttrs']['data-app'] = $app->tab;
                $item['titleAttrs']['title']    = $item['text'];
                if(isset($item['type']) && isset($typeList[$item['type']]))  $item['text']  = array('html' => $item['text'] . '<span class="text-gray ml-1">[' . $typeList[$item['type']] . ']</span>');

                $item['innerClass'] = 'py-0';
                $item['titleClass'] = 'text-clip';
                $item['selected']   = (!empty($selected) && !empty($item['id']) && $selected == $item['id']) || !empty($item['active']);

                if(isset($item['type']) && $item['type'] == 'product')
                {
                    $item['icon'] = 'product';
                }
                elseif(isset($item['type']) && $item['type'] == 'story' && $editType != 'story')
                {
                    $item['actions'] = array();
                    $item['actions']['items'] = array();

                    if($canEdit && $editType != 'task') $item['actions']['items'][] = array('key' => 'edit', 'icon' => 'edit', 'data-toggle' => 'modal', 'url' =>  createLink('tree', 'edit', 'moduleID=' . $item['id'] . '&type=' . ($viewType ? $viewType : $item['type'])));
                    if($itemCanSplit)                   $item['actions']['items'][] = array('key' => 'view',  'icon' => 'split', 'url' => $item['url'], 'data-app' => $app->tab);
                }
                elseif(isset($item['type']) && $item['type'] != 'branch')
                {
                    if($sortTree) $item['trailingIcon'] = 'move muted cursor-move';

                    if(!isset($item['actions']))          $item['actions']          = array();
                    if(!isset($item['actions']['items'])) $item['actions']['items'] = array();

                    if($canEdit)       $item['actions']['items'][] = array('key' => 'edit', 'icon' => 'edit', 'data-toggle' => 'modal', 'url' =>  createLink('tree', 'edit', 'moduleID=' . $item['id'] . '&type=' . $item['type']));
                    if($canDelete)     $item['actions']['items'][] = array('key' => 'delete', 'icon' => 'trash', 'className' => 'btn ghost toolbar-item square size-sm rounded ajax-submit', 'url' => createLink('tree', 'delete', 'module=' . $item['id']));
                    if($itemCanSplit)  $item['actions']['items'][] = array('key' => 'view',  'icon' => 'split', 'url' => $item['url'], 'data-app' => $app->tab);
                }
            }

            if(!empty($item['children']))
            {
                $item['items'] = !empty($item['children']['url']) ? $item['children'] : $this->buildTree($item['children']);
                unset($item['children']);
            }
            unset($item['type']);

            $items[$key] = $item;
        }

        return $items;
    }
}
