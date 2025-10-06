<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'prilabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitylabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'risklabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'collapsebtn' . DS . 'v1.php';

class tableData extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'title?: string',
        'useTable?: bool=true',
        'class?: string',
        'required?: bool=false'
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function getModuleAndMethodForExtend()
    {
        global $app;
        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;

        /* 项目发布和项目版本用自己的工作流。 */
        if($moduleName == 'projectrelease') $moduleName = 'release';
        if($moduleName == 'projectplan')    $moduleName = 'productplan';
        if($moduleName == 'projectbuild')
        {
            $moduleName = 'build';
            if($methodName == 'browse')
            {
                $moduleName = 'execution';
                $methodName = 'build';
            }
        }

        if($moduleName == 'project' && $methodName == 'createtemplate') $methodName = 'create';
        if($moduleName == 'project' && $methodName == 'edittemplate')   $methodName = 'edit';

        /* 反馈转化。 */
        if($moduleName == 'feedback')
        {
            if($methodName == 'tostory')
            {
                $moduleName = 'story';
                $methodName = 'create';
            }
            elseif($methodName == 'touserstory')
            {
                $moduleName = 'requirement';
                $methodName = 'create';
            }
            elseif($methodName == 'toepic')
            {
                $moduleName = 'epic';
                $methodName = 'create';
            }
            elseif($methodName == 'toticket')
            {
                $moduleName = 'ticket';
                $methodName = 'create';
            }
        }

        if($moduleName == 'ticket')
        {
            if($methodName == 'createstory')
            {
                $moduleName = 'story';
                $methodName = 'create';
            }
            elseif($methodName == 'createbug')
            {
                $moduleName = 'bug';
                $methodName = 'create';
            }
        }

        return array($moduleName, $methodName);
    }

    private function buildItemWithTr($item)
    {
        global $app;
        list($moduleName, $methodName) = $this->getModuleAndMethodForExtend();

        static $weightAndRules = array();

        if(empty($weightAndRules))
        {
            $weightAndRules = $app->control->appendAiWeightField($moduleName, $methodName);
        }

        $findNameFromChildren = function($children) use (&$findNameFromChildren)
        {
            foreach($children as $child)
            {
                if(is_object($child) && isset($child->props))
                {
                    $type = $child->props->get('type');
                    if($type == 'hidden') continue;
                    $childName = $child->props->get('name');
                    if(!empty($childName))
                    {
                        $cleanName = preg_replace('/\[\]$/', '', $childName);
                        return $cleanName;
                    }
                }

                if(is_object($child) && method_exists($child, 'children'))
                {
                    $grandChildren = $child->children();
                    if(!empty($grandChildren))
                    {
                        $foundName = $findNameFromChildren($grandChildren);
                        if(!empty($foundName))
                        {
                            return $foundName;
                        }
                    }
                }
            }
            return null;
        };

        $name = $findNameFromChildren($item->children()) ?: '';

        $titleLabel = isset($weightAndRules[$name]['weight']) ? h::label(
            h::div(
                setClass('text ghost form-label-hint text-gray-300 btn square size-sm ai-weight'),
                set::text($weightAndRules[$name]['weight']),
                !empty($weightAndRules[$name]['rule']) ? set('zui-toggle', 'tooltip') : null,
                !empty($weightAndRules[$name]['rule']) ? set('zui-toggle-tooltip', json_encode(array(
                    'title'     => $weightAndRules[$name]['rule'],
                    'className' => 'text-gray border border-gray-300',
                    'type'      => 'white',
                    'placement' => 'top'
                ))) : null,
                $weightAndRules[$name]['weight']
            )
        ) : null;
        $required = $item->prop('required');
        return h::tr
        (
            setClass($item->prop('trClass')),
            h::th
            (
                setClass('py-1.5 pr-2 font-normal nowrap text-right' . ($required ? ' required' : ''), $item->prop('thClass'), !empty($weightAndRules) ? 'ai-weight-th' : ''),
                $item->prop('name'),
                $item->block('suffixName'),
                $titleLabel
            ),
            h::td
            (
                setClass('py-1.5 pl-2 w-full', $item->prop('tdClass')),
                $item->children()
            )
        );
    }

    private function buildItemWithDiv($item)
    {
        if($item->prop('collapse'))
        {
            return div
            (
                setClass('col', 'table-data-tr', $item->prop('trClass')),
                div
                (
                    setClass('py-1.5 pr-2 font-normal nowrap table-data-th', $item->prop('thClass')),
                    $item->prop('name'),
                    new collapseBtn
                    (
                        setClass('w-5 h-5 ml-1'),
                        set::target('.table-data-td'),
                        set::parent('.table-data-tr')
                    )
                ),
                div
                (
                    setClass('py-1.5 pl-2 table-data-td', $item->prop('tdClass')),
                    $item->children()
                )
            );
        }

        return div
        (
            setClass('flex table-data-tr', $item->prop('trClass')),
            div
            (
                setClass('py-1.5 pr-2 font-normal nowrap table-data-th', $item->prop('thClass')),
                $item->prop('name')
            ),
            div
            (
                setClass('py-1.5 pl-2 table-data-td', $item->prop('tdClass')),
                $item->children()
            )
        );
    }

    public function onBuildItem($item)
    {
        $item->setProp(array('thClass' => $this->prop('thClass'), 'tdClass' => $this->prop('tdClass')));

        $useTable = $this->prop('useTable');
        if($useTable) return $this->buildItemWithTr($item);

        return $this->buildItemWithDiv($item);
    }

    private function caption()
    {
        $title = $this->prop('title');
        if(empty($title)) return null;

        return h::caption
        (
            setClass('text-lg font-bold text-left mb-2'),
            $title
        );
    }

    protected function build()
    {
        $useTable   = $this->prop('useTable');
        $tableClass = $this->prop('class');
        if($useTable)
        {
            return h::table
            (
                setClass('table-data'),
                $tableClass ? setClass($tableClass) : null,
                $this->caption(),
                h::tbody($this->children()),
                html($this->getAiWeightCSS())
            );
        }

        return div
        (
            setClass('table-data'),
            $tableClass ? setClass($tableClass) : null,
            div
            (
                setClass('table-data-body'),
                $this->children()
            )
        );
    }

    public static function getAiWeightCSS()
    {
        return "<style>.ai-weight{border: 1px solid #3883fb !important; color: #3883fb !important;background-color: rgba(56, 131, 251, 0.1) !important;border-radius: 4px !important;padding: 2px 6px !important;font-weight: 500 !important;} .ai-weight-th{width: 96px !important;}</style>";
    }
}
