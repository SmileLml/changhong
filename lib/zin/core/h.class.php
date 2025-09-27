<?php
/**
 * The html element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'node.class.php';
require_once __DIR__ . DS . 'text.class.php';
require_once __DIR__ . DS . 'directive.class.php';

class h extends node
{
    /**
     * @var mixed[]
     */
    public static $h5Tags = array('div', 'span', 'strong', 'small', 'code', 'canvas', 'br', 'a', 'p', 'img', 'button', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ol', 'ul', 'li', 'template', 'fieldset', 'legend', 'iframe');

    /**
     * @var mixed[]
     */
    public static $defineProps = array
    (
        'tagName'   => 'string',
        'selfClose' => '?bool'
    );

    public function tagName()
    {
        $tagName = $this->prop('tagName');
        return $tagName === null ? '' : $tagName;
    }

    public function fullType()
    {
        return 'zin\\' . $this->tagName();
    }

    public function type()
    {
        return $this->tagName();
    }

    public function isSelfClose()
    {
        $selfClose = $this->prop('selfClose');
        if($selfClose !== null) return boolval($selfClose);

        return in_array($this->tagName(), static::$selfCloseTags);
    }

    /**
     * @param mixed[]|string $prop
     * @param mixed $value
     */
    protected function onSetProp($prop, $value)
    {
        if($prop === 'className') $prop = 'class';
        return parent::onSetProp($prop, $value);
    }

    /**
     * @return mixed
     */
    public function build()
    {
        if($this->isSelfClose()) return $this->buildSelfCloseTag();

        $content = array($this->buildTagBegin());
        $build   = parent::build();

        if(is_array($build))  $content = array_merge($content, $build);
        else                  $content[] = $build;

        $content[] = $this->buildTagEnd();
        return $content;
    }

    protected function getPropsStr()
    {
        $propStr = $this->props->toStr(array_keys(static::definedPropsList()));
        if($this->props->hasEvent() && empty($this->id()) && $this->tagName() !== 'html') $propStr = "$propStr id='$this->gid'";
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildSelfCloseTag()
    {
        $tagName = $this->tagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr />";
    }

    protected function buildTagBegin()
    {
        $tagName = $this->tagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr>";
    }

    protected function buildTagEnd()
    {
        $tagName = $this->tagName();
        return "</$tagName>";
    }

    /**
     * @param mixed ...$args
     * @param string $tagName
     */
    public static function create($tagName, ...$args)
    {
        $h = new h(...$args);
        $h->setProp('tagName', $tagName);
        return $h;
    }

    /**
     * @param string $tagName
     * @param mixed[] $args
     */
    public static function __callStatic($tagName, $args)
    {
        return static::create($tagName, ...$args);
    }

    public static function a()
    {
        $a = static::create('a', func_get_args());
        if($a->prop('target') === '_blank' && !$a->hasProp('rel'))
        {
            $a->setProp('rel', 'noopener noreferrer');
        }
        return $a;
    }

    /**
     * @param mixed ...$args
     */
    public static function button(...$args)
    {
        $button = static::create('button', ...$args);
        $button->setDefaultProps('type', 'button');
        return $button;
    }

    /**
     * @param mixed ...$args
     */
    public static function input(...$args)
    {
        $input = static::create('input', ...$args);
        if($input->prop('type') === 'file')
        {
            $name = $input->prop('name');
            if($name && !str_contains($name, '[')) $input->setProp('name', "{$name}[]");
        }
        else
        {
            $input->setDefaultProps('type', 'text');
        }
        return $input;
    }

    /**
     * @param mixed $value
     * @param mixed ...$args
     * @param string $name
     */
    public static function formHidden($name, $value, ...$args)
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps(array('type' => 'hidden', 'name' => $name, 'value' => strval($value)));
        return $input;
    }

    /**
     * @param mixed ...$args
     */
    public static function checkbox(...$args)
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps('type', 'checkbox');
        return $input;
    }

    /**
     * @param mixed ...$args
     */
    public static function radio(...$args)
    {
        $input = static::create('input', ...$args);
        $input->setDefaultProps('type', 'radio');
        return $input;
    }

    /**
     * @param mixed ...$args
     */
    public static function textarea(...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        return static::create('textarea', $code, $args);
    }

    /**
     * create a html comment tag <!--...-->
     *
     * @access public
     * @param  string $comment
     * @return node
     */
    public static function comment($comment)
    {
        return html("<!-- $comment -->");
    }

    /**
     * @param mixed ...$args
     * @param string $src
     */
    public static function importJs($src, ...$args)
    {
        $script = static::create('script', ...$args);
        $script->setDefaultProps('src', static::formatResourceUrl($src));
        return $script;
    }

    /**
     * @param mixed ...$args
     * @param string $href
     */
    public static function importCss($href, ...$args)
    {
        $link = static::create('link', ...$args);
        $link->setDefaultProps(array('rel' => 'stylesheet', 'href' => static::formatResourceUrl($href)));
        return $link;
    }

    /**
     * @param string $url
     */
    public static function formatResourceUrl($url)
    {
        global $config;
        $pathInfo = parse_url($url);
        $mark  = !empty($pathInfo['query']) ? '&' : '?';
        return "$url{$mark}v={$config->version}";
    }

    /**
     * @param mixed ...$args
     * @param string $url
     */
    public static function favicon($url, ...$args)
    {
        return array
        (
            static::create('link', set(array('rel' => 'icon', 'href' => $url, 'type' => 'image/x-icon')), ...$args),
            static::create('link', set(array('rel' => 'shortcut icon', 'href' => $url, 'type' => 'image/x-icon')), ...$args)
        );
    }

    /**
     * @param string|mixed[] $file
     * @param mixed ...$args
     * @param string|null $type
     */
    public static function import($file, $type = null, ...$args)
    {
        if(is_array($file))
        {
            $children = array();
            foreach($file as $file)
            {
                $children[] = static::import($file, $type);
            }
            return $children;
        }
        if($type === null) $type = pathinfo($file, PATHINFO_EXTENSION);
        if($type == 'js' || $type == 'cjs') return static::importJs($file, $args);
        if($type == 'css') return static::importCss($file, $args);
        return null;
    }

    /**
     * @param mixed ...$args
     */
    public static function css(...$args)
    {
        list($code, $args) = h::splitRawCode($args);
        if(empty($code)) return null;
        return static::create('style', html(...$code), $args);
    }

    /**
     * @param mixed ...$args
     */
    public static function globalJS(...$args)
    {
        list($code, $args) = h::splitRawCode($args, true);
        if(empty($code)) return null;
        return static::create('script', html(...$code), $args);
    }

    /**
     * @param mixed ...$args
     */
    public static function js(...$args)
    {
        list($code, $args) = h::splitRawCode($args, true);
        if(empty($code)) return null;
        $code = ';(function(){' . implode("\n", $code) . '}());';
        return static::create('script', html($code), ...$args);
    }

    /**
     * @param mixed $value
     * @param mixed ...$args
     * @param string $name
     */
    public static function jsVar($name, $value, ...$args)
    {

        return static::js(js()->var($name, $value), $args);
    }

    /**
     * @param mixed ...$args
     * @param string $funcName
     */
    public static function jsCall($funcName, ...$args)
    {
        $args  = func_get_args();
        $funcName  = array_shift($args);

        $funcArgs   = array();
        $directives = array();
        foreach($args as $arg)
        {
            if(isDirective($arg)) $directives[] = $arg;
            else                  $funcArgs[] = $arg;
        }

        if(str_starts_with($funcName, '~'))
        {
            $funcName = substr($funcName, 1);
            $js = js()->call('$', jsCallback()->do(js()->call($funcName, ...$funcArgs)));
        }
        else
        {
            $js = js()->call($funcName, ...$funcArgs);
        }

        return static::js($js, $directives);
    }

    protected static function splitRawCode($children, $includeJS = false)
    {
        $children = \zin\utils\flat($children);
        $code = array();
        $args = array();
        foreach($children as $child)
        {
            if($includeJS && $child instanceof js) $child = $child->toJS();

            if(is_string($child)) $code[] = $child;
            else                  $args[] = $child;
        }
        return array($code, $args);
    }

    public static $selfCloseTags = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');
}
