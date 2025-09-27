<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'storylist' . DS . 'v1.php';

class linkedStoryList extends storyList
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'story'          => '?object',      // 被关联的需求。
        'unlinkBtn'      => '?array|bool',  // 取消关联按钮。
        'unlinkStoryTip' => '?string',      // 取消关联提示信息。
        'unlinkUrl'      => '?string',      // 取消关联需求链接
        'newLinkBtn'     => '?array|bool'   // 底部新关联按钮。
    );

    /**
     * @var mixed[]
     */
    protected static $defaultProps = array
    (
        'name' => 'linked-story-list'
    );

    public static function getPageJS()
    {
        return <<<'JS'
window.unlinkStory = function(e)
{
    const $this = $(e.target).closest('li').find('.unlinkStory');
    zui.Modal.confirm({message: window.unlinkStoryTip || $this.closest('ul').data('unlinkStoryTip'), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($this.attr('data-url'), function(){$this.closest('li').remove()});
    });
};
JS;
    }

    /**
     * @var object|null
     */
    protected $story;

    /**
     * @var string|null
     */
    protected $unlinkUrl;

    /**
     * @var null|mixed[]|bool
     */
    protected $unlinkBtn = null;

    protected function beforeBuild()
    {
        $story = $this->prop('story');
        if(!$story) $story = data('story');
        if(!$story) return;

        $unlinkUrl = $this->prop('unlinkUrl');
        if($unlinkUrl === null) $unlinkUrl = createLink('story', 'linkStory', "storyID={$story->id}&type=remove&linkedID={id}&browseType=&queryID=0&storyType=$story->type");

        $canLink = hasPriv($story->type, 'linkStory');
        $unlinkBtn = $this->prop('unlinkBtn');
        if($unlinkBtn === null)  $unlinkBtn = $canLink;

        $this->story     = $story;
        $this->unlinkUrl = $unlinkUrl;
        $this->unlinkBtn = $unlinkBtn;
    }

    /**
     * @param object $story
     */
    protected function getItem($story)
    {
        global $lang;

        $item      = parent::getItem($story);
        $unlinkBtn = $this->unlinkBtn;

        if($unlinkBtn)
        {
            if(!isset($item['actions'])) $item['actions'] = array();
            $btn = array
            (
                'class'       => 'unlinkStory unlink opacity-0 group-hover:opacity-100 text-primary',
                'icon'        => 'unlink',
                'data-id'     => $story->id,
                'data-on'     => 'click',
                'data-url'    => str_replace('{id}', "$story->id", $this->unlinkUrl),
                'data-params' => 'event',
                'data-call'   => 'unlinkStory',
                'hint'        => $lang->story->unlink
            );

            if(is_array($unlinkBtn)) $btn = array_merge($btn, $unlinkBtn);

            $item['actions'][] = $btn;
        }

        return $item;
    }

    protected function build()
    {
        $list = parent::build();

        if($this->unlinkBtn)
        {
            $unlinkStoryTip = $this->prop('unlinkStoryTip');
            if($unlinkStoryTip === null && $this->story)
            {
                global $lang;
                $unlinkStoryTip = $this->story->type == 'story' ? str_replace($lang->SRCommon, $lang->URCommon, $lang->story->unlinkStory) : $lang->story->unlinkStory;
            }

            $list->add(setData('unlinkStoryTip', $unlinkStoryTip));
        }

        $newLinkBtn = $this->prop('newLinkBtn');
        if($newLinkBtn === null) $newLinkBtn = $this->unlinkBtn;
        if($newLinkBtn)
        {
            global $lang;
            $story = $this->story;
            if(!$story) return $list;

            $btn = new btn
            (
                set::url('story', 'linkStory', "storyID=$story->id&type=linkStories&linkedID=0&browseType=&queryID=0&storyType=$story->type"),
                set::icon('plus'),
                set::size('sm'),
                set::type('secondary'),
                setClass('my-2'),
                setData(array('toggle' => 'modal', 'size' => 'lg')),
                setID('linkButton'),
                is_array($newLinkBtn) ? set($newLinkBtn) : null,
                $lang->story->linkStory
            );
            return array($list, $btn);
        }
        return $list;
    }
}
