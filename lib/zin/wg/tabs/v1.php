<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'tabpane' . DS . 'v1.php';

class tabs extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        /* Tabs direction: h - horizontal, v - vertical */
        'direction?:string="h"',
        'collapse?: bool=false',
        'headerClass?:string=""',
        'navClass?:string=""',
        'titleClass?:string="font-bold text-md"'
    );

    public static function getPageCSS()
    {
        return <<<CSS
.tabs-header {position: relative; z-index: 1}
.tabs-nav>.nav-item>a {padding: 0; padding-right: 0; color: var(--color-gray-800);}
.tabs-nav>.nav-item>a:after {border-width: 0;}
.tabs-nav>.nav-item>a:before {background: none;}
.tabs-nav>.nav-item>a.active {color: var(--color-primary-500);}
.tabs-nav>.nav-item>a.active:after {border-bottom-color: var(--color-primary-500) !important; border-bottom-width: 2px;}
.tabs-nav>.divider {height: 20px; border-right: 1px solid #DDD;}
.tabs-collapse-btn {position: absolute; top: 0; right: 0; width: 24px; height: 24px;}
.tab-content {padding-top: 10px;}
CSS;
    }

    /**
     * @param \zin\tabPane $tabPane
     * @param string $titleClass
     */
    protected function buildTitleView($tabPane, $titleClass = "")
    {
        $key      = $tabPane->prop('key');
        $title    = $tabPane->prop('title');
        $active   = $tabPane->prop('active');
        $hide     = $tabPane->prop('hide');
        $param    = $tabPane->prop('param');
        $prefix   = $tabPane->block('prefix');
        $suffix   = $tabPane->block('suffix');
        $navClass = $this->prop('navClass');

        return li
        (
            setClass('nav-item', $navClass, $hide ? 'hidden' : ''),
            setData('key', $key),
            a
            (
                set('data-toggle', 'tab'),
                set('data-param', $param),
                setClass('font-medium', $active ? 'active' : null, $titleClass),
                set::href("#$key"),
                $prefix,
                span($title),
                $suffix
            )
        );
    }

    /**
     * @param array $titleViews
     * @return node
     */
    protected function buildTabHeader($titleViews)
    {
        $isVertical  = $this->prop('direction') === 'v';
        $collapse    = $this->prop('collapse');
        $headerClass = $this->prop('headerClass');

        return div
        (
            setClass('tabs-header bg-white'),
            ul
            (
                setClass('tabs-nav nav nav-tabs gap-x-5', $collapse ? 'relative' : null, $headerClass),
                $isVertical ? setClass('nav-stacked') : null,
                $titleViews
            ),
            $this->buildCollapseBtn()
        );
    }

    /**
     * @param array $titleViews
     * @return node
     * @param mixed[] $contentViews
     */
    protected function buildTabBody($contentViews)
    {
        return div
        (
            setClass('tab-content'),
            $contentViews
        );
    }

    private function processTabs()
    {
        $tabPanes   = array();
        $children   = array();
        $hasActived = false;

        foreach ($this->children() as $child)
        {
            if($child instanceof tabPane)
            {
                $tabPanes[] = $child;
                if($child->prop('active')) $hasActived = true;
                continue;
            }

            $children[] = $child;
        }

        if(!$hasActived && !empty($tabPanes)) $tabPanes[0]->setProp('active', true);
        return array($tabPanes, $children);
    }

    private function buildCollapseBtn()
    {
        $collapse = $this->prop('collapse');
        if(!$collapse) return null;

        return collapseBtn
        (
            setClass('tabs-collapse-btn'),
            set::target('.tab-content'),
            set::parent('.tabs')
        );
    }

    protected function build()
    {
        $isVertical = $this->prop('direction') === 'v';
        $titleClass = $this->prop('titleClass');

        list($tabPanes, $children) = $this->processTabs();

        $titleViews = array();
        foreach($tabPanes as $tabPane)
        {
            $titleViews[] = $this->buildTitleView($tabPane, $titleClass);
            if($tabPane->prop('divider')) $titleViews[] = div(set::className('divider'));
        }

        return div
        (
            setClass('tabs', $isVertical ? 'flex' : null),
            set($this->getRestProps()),

            $this->buildTabHeader($titleViews),
            $this->buildTabBody($tabPanes),
            $children
        );
    }
}
