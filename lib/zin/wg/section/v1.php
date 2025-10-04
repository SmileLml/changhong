<?php
namespace zin;

class section extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'title?: string',         // 标题
        'content?: string|array', // 内容
        'useHtml?: bool=false',   // 内容是否解析 HTML 标签
        'required?: bool=false'   // 标题上是否显示必填标记
    );

    /**
     * @var mixed[]
     */
    protected static $defineBlocks = array(
        'subtitle' => array(),
        'actions'  => array()
    );

    /**
     * @param mixed $child
     */
    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('content'))
        {
            $this->props->set('content', $child);
            return false;
        }
    }

    private function title($weightAndRules = array(), $name = '')
    {
        $title       = $this->prop('title');
        $actionsView = $this->block('actions');
        $required    = $this->prop('required');
        $titleLabel  = isset($weightAndRules[$name]['weight']) ? h::label(
            h::div(
                setClass('text ghost form-label-hint text-gray-300 btn square size-sm ai-weight'),
                set::text($weightAndRules[$name]['weight']),
                !empty($weightAndRules[$name]['rule']) ? set('zui-toggle', 'tooltip') : null,
                !empty($weightAndRules[$name]['rule']) ? set('zui-toggle-tooltip', json_encode(array(
                    'title'       => $weightAndRules[$name]['rule'],
                    'className'   => 'text-gray border border-gray-300',
                    'type'        => 'white',
                    'placement'   => 'right'
                ))) : null,
                $weightAndRules[$name]['weight']
            )
        ) : null;
        if(empty($actionsView))
        {
            return div
            (
                setClass('font-bold text-md', 'mb-2', 'inline-flex'),
                $required ? h::label(setClass('form-label required mr-1 pb-3')) : null,
                $title,
                $titleLabel
            );
        }

        return div
        (
            setClass('flex', 'items-center', 'mb-2'),
            div
            (
                setClass('font-bold text-md', 'inline-flex'),
                $required ? h::label(setClass('form-label required mr-1')) : null,
                $title,
                $titleLabel
            ),
            $actionsView
        );
    }

    /**
     * @param string|\zin\node $content
     */
    private function content($content)
    {
        $useHtml = $this->prop('useHtml') === true && is_string($content);

        return div
        (
            setClass('article'),
            $useHtml ? html($content) : $content
        );

    }

    /**
     * @return \zin\node|mixed[]|null
     */
    private function buildContent()
    {
        $content = $this->prop('content');
        if(!isset($content)) return null;

        return $this->content($content);
    }

    private function getAiWeightCSS()
    {
        return "<style>.ai-weight{margin-left: 5px !important; border: 1px solid #3883fb !important; color: #3883fb !important;background-color: rgba(56, 131, 251, 0.1) !important;border-radius: 4px !important;padding: 2px 6px !important;font-weight: 500 !important;}</style>";
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

    protected function build()
    {
        global $app;
        list($moduleName, $methodName) = $this->getModuleAndMethodForExtend();
        $children = $this->children();
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
        $name = $findNameFromChildren($children) ?: '';

        static $weightAndRules = array();

        if(empty($weightAndRules))
        {
            $weightAndRules = $app->control->appendAiWeightFieldTipForSection($moduleName, $methodName);
        }

        return div
        (
            setClass('section'),
            set($this->getRestProps()),
            $this->title($weightAndRules, $name),
            $this->block('subtitle'),
            $this->buildContent(),
            $this->children(),
            html($this->getAiWeightCSS())
        );
    }
}
