<?php
/**
 * The ajaxGetDropmenuForOld view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */

$noDataHtml       = "<div class='text-gray text-center nodata'>{$lang->noData}</div>";
$browserSetting   = $config->message->browser;
$buildMessageList = function($messageGroup) use ($lang, $noDataHtml)
{
    if(empty($messageGroup)) return $noDataHtml;

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $itemList = array();
        foreach($messages as $message)
        {
            $isUnread = $message->status != 'read';
            $dotColor = $isUnread ? 'danger' : 'gray';

            $li  = "<li class='message-item border rounded-lg p-2 mt-2" . ($isUnread ? ' unread' : '') . "' data-msgid='{$message->id}' style='word-break: break-all;' onclick='markRead(this)'>\n";
            $li .= "<div class='text-gray relative'>";
            $li .= "<div><span class='label label-dot mr-2 {$dotColor}'></span><span>{$lang->message->browser}</span></div>\n";
            $li .= "<div class='absolute' style='top:0px; right:0px;'><span>{$message->showTime}</span><i class='icon icon-close ml-2 cursor-pointer delete-message-btn' onclick='deleteMessage(this)'></i></div>\n";
            $li .= "</div>\n";
            $li .= "<div class='pt-1'>{$message->data}</div>\n";
            $li .= "</li>\n";
            $itemList[] = $li;
        }
        $dateList[] = "<li class='message-date mt-2'>{$date}\n <ul class='list-unstyled'>" . implode("\n", $itemList) . "</ul>";
    }
    return "<ul class='list-unstyled'>" . implode("\n", $dateList) . "</ul>";
};
?>

<style>
#messageTabs.text-black {color: rgb(49, 60, 82);}
#messageTabs.pt-10px {padding-top:10px;}
#messageTabs.px-5 {padding-left: 20px; padding-right: 20px;}
#messageTabs.pb-5 {padding-bottom: 20px;}
#messageTabs .border {border-width: 1px; border-color: rgb(235, 237, 243); border-style: solid;}
#messageTabs .rounded-lg {border-radius: 6px;}
#messageTabs .p-2 {padding:8px;}
#messageTabs .pt-2 {padding-top:8px;}
#messageTabs .pl-2 { padding-left: 8px; }
#messageTabs .pb-2 { padding-bottom: 8px; }
#messageTabs .pt-2 { padding-top: 8px; }
#messageTabs .mt-2 {margin-top:8px;}
#messageTabs .mr-2 {margin-right:8px;}
#messageTabs .ml-2 {margin-left:8px;}
#messageTabs .cursor-pointer {cursor: pointer;}
#messageTabs .tabs-header{border-bottom-width: 1px;}
#messageTabs .label-dot{width:5px; height:5px; vertical-align: middle;}
#messageTabs .label-dot.gray { background-color: rgb(100, 117, 139); box-shadow: rgb(255, 255, 255) 0px 0px 0px 0px, rgb(100, 117, 139) 0px 0px 0px 1px, rgba(0, 0, 0, 0) 0px 0px 0px 0px;}
#messageTabs .w-52{width:208px;}
#messageTabs .top-3{top:12px;}
#messageTabs .right-5{right:20px;}
#messageTabs .btn-link{background: 0 0; border-color:rgba(0, 0, 0, 0);}
#messageTabs .w-5\/6 { width: 83.333333%; }
#messageTabs .border-b { border-bottom: 1px solid rgb(235, 237, 243); }
#messageTabs .font-bold { font-weight: 700; }
#messageTabs .justify-center { justify-content: center; }
#messageTabs #messageSettingDropdown { padding: 8px;}
#messageTabs .message-item a[data-url]{color: rgb(43, 71, 162);}

#messageTabs .form, #messageTabs .form-label { display: flex;}
#messageTabs .form {flex-direction: column; gap: 4px;}
#messageTabs .form-horz .form-group,
#messageTabs .form-horz .form-row { align-items: flex-start; display: flex; flex-direction: row;}
#messageTabs .form-horz .form-group { flex-grow: 1; flex-wrap: wrap; min-height: 32px; min-width: 0; padding-left: 96px; position: relative; }
#messageTabs .form-horz .form-group.no-label { padding-left: 0; }
#messageTabs .form-label { align-items: center; color: #3d4667; flex-direction: row; gap: 4px; height: 32px; overflow: hidden; position: relative; text-overflow: ellipsis; white-space: nowrap; }
#messageTabs .form-horz .form-label { justify-content: flex-end; left: 0; padding-left: 16px; padding-right: 8px; position: absolute; top: 0; width: 96px; }

.switch { display: flex; gap: .6px; min-height: 20px; padding-left: 40px; position: relative; }
.switch>input { bottom: 0; left: 0; opacity: 0; position: absolute; right: 0; top: 0; }
.switch>label:before { cursor: pointer; box-shadow:rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.05) 0px 2px 4px 0px inset; }
.switch>label:after, .switch>label:before {
    border-radius: 9999px;
    border-width: 1px;
    height: 16px;
    left: 0;
    position: absolute;
    top: 2px;
    width: 32px;
    border-color: rgb(210, 214, 229);
    background-color: rgb(239, 241, 243);
    transition-duration: .3s;
    transition-property: all;
    transition-timing-function: cubic-bezier(.4,0,.2,1);
    content: "";
}
.switch>label:after {
    height: 12px;
    left: 2px;
    top: 4px;
    width: 12px;
    border-color: rgb(255, 255, 255);
    background-color: rgb(255, 255, 255);
    box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.04) 0px 3px 8px 0px, rgba(0, 0, 0, 0.01) 0px 1px 2px -1px;
}
.switch>input:checked+label:before { background-color: rgb(56, 131, 250); border-color: rgba(var(--color-inverse-rgb), .2); }
.switch>label:hover:before { border-color: gba(235, 237, 243); }
.switch>input:checked+label:after { color: #3883fa; left: 18px; }

#header #dropdownMessageMenu{background-color: #fff; overflow-y: auto; max-height: calc(100vh - 50px); min-height: 280px;}
</style>

<div id='messageTabs' class='text-black pt-10px px-5 pb-5 relative' style='width:400px; background-color:#fff; color: rgb(49, 60, 82)'>
  <ul class="nav nav-tabs">
    <li class='<?php echo $active == 'unread' ? 'active' : '';?>'><a data-tab href="#unread-messages"><span><?php printf($lang->message->unread, $unreadCount)?></span></a></li>
    <li class='<?php echo $active == 'all'    ? 'active' : '';?>'><a data-tab href="#all-messages"><span><?php echo $lang->message->all;?></span></a></li>
  </ul>
  <div class="tab-content" style='padding-top: 10px;'>
    <div class="tab-pane <?php echo $active == 'unread' ? 'active' : '';?>" id="unread-messages">
      <?php echo $buildMessageList($unreadMessages);?>
    </div>
    <div class="tab-pane <?php echo $active == 'all' ? 'active' : '';?>" id="all-messages">
      <?php echo $buildMessageList($allMessages);?>
    </div>
  </div>
  <div class="absolute top-3 right-5" style="z-index: 100;">
    <?php echo html::commonButton("", "title='{$lang->message->notice->allMarkRead}' onclick='markAllRead()'", 'allMarkRead btn btn-sm btn-link', 'clear');?>
    <?php echo html::commonButton("", "title='{$lang->message->notice->clearRead}' onclick='clearRead()'", 'clearRead btn btn-sm btn-link', 'trash');?>
    <span class='messageSettingBox relative'>
      <?php echo html::commonButton("", "id='messageSettingDropdown-toggle' title={$lang->message->browserSetting->more} onclick='toggleSettingDropdown()'", 'btn btn-sm btn-link', 'cog-outline');?>
      <div class="dropdown-menu w-52 absolute" id="messageSettingDropdown" style='left:-170px;top:25px;'>
        <form class="form ajaxForm form-horz" action="<?php echo inlink('ajaxSetOneself');?>" method="post">
          <div class="form-row font-bold border-b pb-2 pl-2 pt-2"><?php echo $lang->message->browserSetting->more;?></div>
          <div class="form-group" style="align-items: center;">
            <label class="form-label"><?php echo $lang->message->browserSetting->show;?></label>
            <div class="switch">
              <input type="checkbox" id="show_1" name="show" <?php echo $browserSetting->show ? "checked='checked'" : ''; ?> value="1">
              <label for="show_1">&nbsp; </label>
            </div>
          </div>
          <div class="form-group" style="align-items: center;">
            <label class="form-label"><?php echo $lang->message->browserSetting->count;?></label>
            <div class="switch">
              <input type="checkbox" id="count_1" name="count" <?php echo $browserSetting->count ? "checked='checked'" : ''; ?> value="1">
              <label for="count_1">&nbsp; </label>
            </div>
          </div>
          <div class="form-group w-5/6">
            <label class="form-label"><?php echo $lang->message->browserSetting->maxDays;?></label>
            <div class="input-control has-icon-right">
              <?php echo html::input('maxDays', $browserSetting->maxDays, "class='form-control'");?>
              <label for='maxDays' class="input-control-icon-right"><?php echo $lang->day;?></label>
            </div>
          </div>
          <div class="form-group justify-center form-actions mt-2 no-label">
            <?php echo html::submitButton($lang->save,   "style='min-width:20px;'", "btn btn-sm btn-primary");?>
            <?php echo html::commonButton($lang->cancel, "style='min-width:20px;' onclick='closeSettingDropdown()'", "btn btn-sm");?>
          </div>
        </form>
      </div>
    </span>
  </div>
</div>

<?php
js::set('unreadLangTempate', $lang->message->unread);
js::set('noDataHtml', $noDataHtml);
js::set('showCount', $config->message->browser->count);
?>
<script>
window.markRead = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
    let isUnread = $this.hasClass('unread');
    if(!isUnread) return;

    let messageID    = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item.unread[data-msgid="' + messageID + '"]');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $messageItem.attr('data-target', '#readContextMenu');
    $.get($.createLink('message', 'ajaxMarkRead', "id=" + messageID));

    /* Rerender unread count. */
    $('#messageTabs #unread-messages.tab-pane').find('.message-item[data-msgid="' + messageID + '"]').addClass('hidden');
    renderMessage();
};

window.markUnread = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
    let isUnread = $this.hasClass('unread');
    if(isUnread) return;

    let messageID    = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item[data-msgid="' + messageID + '"]');
    $messageItem.find('.label-dot.gray').removeClass('gray').addClass('danger');
    $messageItem.addClass('unread');
    $.get($.createLink('message', 'ajaxMarkUnread', "id=" + messageID));

    /* Rerender unread count. */
    fetchMessage(true, $.createLink('message', 'ajaxGetDropmenuForOld', 'active=all'));
    renderMessage();
};

window.markAllRead = function()
{
    let $messageItem = $('#messageTabs .message-item.unread');
    $messageItem.find('.label-dot.danger').removeClass('danger').addClass('gray');
    $messageItem.removeClass('unread');
    $('#messageTabs #unread-messages.tab-pane .message-item').addClass('hidden');
    $.get($.createLink('message', 'ajaxMarkRead', "id=all"));
    renderMessage();
};

window.clearRead = function()
{
    let $messageItem = $('#messageTabs .message-item:not(.unread)');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=allread"));
    renderMessage();
};

window.deleteMessage = function(obj)
{
    let $this = $(obj);
    if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');

    let messageID = $this.data("msgid");
    let $messageItem = $('#messageTabs .message-item[data-msgid="' + messageID + '"]');
    $messageItem.removeClass('unread');
    $messageItem.addClass('hidden');
    $.get($.createLink('message', 'ajaxDelete', "id=" + messageID));

    /* Rerender unread count. */
    renderMessage();
};

window.clickMessage = function(obj)
{
    let $obj = $(obj);
    let url  = $obj.attr('data-url').replace(/\?onlybody=yes/g, '').replace(/\&onlybody=yes/g, '');
    markRead(obj);
    $('#header #messageBar').trigger('click');
    $.apps.openApp(url);
    renderMessage();
};

window.renderMessage = function()
{
    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(typeof(unreadLangTempate) != 'undefined') $('[href="#unread-messages"] span').html(unreadLangTempate.replace(/%s/, unreadCount));
    if(unreadCount == 0)
    {
        $unreadTab.find('ul').addClass('hidden');
        if($unreadTab.find('.nodata').length == 0) $unreadTab.append(noDataHtml);
    }

    let $allTab  = $('#messageTabs #all-messages.tab-pane');
    let allCount = $allTab.find('.message-item:not(.hidden)').length;
    if(allCount == 0)
    {
        $allTab.find('ul').addClass('hidden');
        if($allTab.find('.nodata').length == 0) $allTab.append(noDataHtml);
    }

    $('#messageTabs .message-date').each(function()
    {
        if($(this).find('.message-item:not(.hidden)').length == 0) $(this).addClass('hidden');
    });

    updateAllDot(showCount);
};

window.updateAllDot = function(showCount)
{
    showCount      = showCount != '0';
    let dotStyle   = 'padding: 2px;';
    let rightStyle = showCount ? 'right: -10px;' : 'right: -2px;';
    if(!showCount) dotStyle += 'width: 5px; height: 5px;';

    let $unreadTab  = $('#messageTabs #unread-messages.tab-pane');
    let unreadCount = $unreadTab.find('.message-item.unread').length;
    if(unreadCount < 10 && showCount) rightStyle = 'right: -5px;';
    if(unreadCount > 99) unreadCount = '99+';

    dotStyle += showCount ? 'top: -3px; aspect-ratio: 0;' : 'top: -2px; aspect-ratio: 1 / 1;';
    dotStyle += rightStyle;

    let dotHtml = '<span class="label danger label-dot absolute' + (showCount ? ' rounded-sm' : '') + '" style="' + dotStyle + '">' + (showCount ? unreadCount : '') + '</span>';
    top.$('#apps .app-container').each(function()
    {
        let $iframeMessageBar = $(this).find('iframe').contents().find('#messageBar');
        if($iframeMessageBar.length > 0)
        {
            $iframeMessageBar.find('.label-dot.danger').remove();
            if(unreadCount) $iframeMessageBar.append(dotHtml);
        }

        let $oldPage = $(this).find('iframe').contents().find('#oldPage');
        if($oldPage.length > 0)
        {
            $iframeMessageBar = $oldPage.find('iframe').contents().find('#messageBar');
            if($iframeMessageBar.length  == 0) return;

            $iframeMessageBar.find('.label-dot.danger').remove();
            if(unreadCount) $iframeMessageBar.append(dotHtml);
        }
    });
};

window.hideContextMenu = function()
{
    if($.zui.ContextMenu.isShow()) $.zui.ContextMenu.hide();
    contextmenuEle = null;
};

window.clickContextMenu = function(item)
{
    if(contextmenuEle == null) return;

    let action = item.value;
    let $this  = contextmenuEle;
    if(action == 'delete')     deleteMessage($this);
    if(action == 'markunread') markUnread($this);
};

window.toggleSettingDropdown = function(isOpen)
{
    if(typeof(isOpen) == 'undefined') isOpen = $('#messageSettingDropdown-toggle').hasClass('open');
    $('#messageSettingDropdown-toggle').toggleClass('open', !isOpen);
    $('#messageSettingDropdown').toggleClass('show', !isOpen);
};

window.closeSettingDropdown = function(){ toggleSettingDropdown(true); }
window.reloadSettingModal   = function(showCount)
{
    updateAllDot(showCount);
    setTimeout(function()
    {
        $('#dropdownMessageMenu #messageSettingDropdown').find('form :submit').popover('destroy');
        closeSettingDropdown();
    }, 1000)
};

let contextmenuEle = null;
$(function()
{
    $.ajaxForm('.ajaxForm');
    updateAllDot(showCount);

    let unreadContextMenu = [{'label': '<?php echo $lang->delete;?>', 'value': 'delete'}];
    let readContextMenu   = [
        {'label': '<?php echo $lang->delete;?>', 'value': 'delete'},
        {'label': '<?php echo $lang->message->markUnread;?>', 'value': 'markunread'}
    ];

    /* Bind contextmenu for message dropdown. */
    $(document).on('contextmenu', '*', function(event)
    {
        hideContextMenu();
        let $this = $(this);
        if($this.hasClass('message-item') || $this.closest('.message-item').length)
        {
            event.preventDefault();
            event.stopPropagation();

            if(!$this.hasClass('message-item')) $this = $this.closest('.message-item');
            $.zui.ContextMenu.show($this.hasClass('unread') ? unreadContextMenu : readContextMenu,
            {
                event: event,
                onClickItem: function(item) {clickContextMenu(item);}
            }, function() { contextmenuEle = $this; });
            return;
        }
    });

    /* Hidden dropdown and contextmenu. */
    $(document).on('click', '*', function(event)
    {
        hideContextMenu();
        let $this = $(this);
        if($this.hasClass('.contextmenu-menu') || $this.closest('.contextmenu-menu').length)
        {
            event.stopPropagation();
            return;
        }
        if($this.attr('id') == 'messageDropdown' || $this.closest('#messageDropdown').length)
        {
            if($this.attr('id') == 'messageDropdown') event.stopPropagation();

            if($(event.target).closest('.messageSettingBox').length == 0 && $('#messageSettingDropdown-toggle').hasClass('open')) closeSettingDropdown();
            return;
        }

        $('#messageDropdown #messageBar').removeClass('open');
        $('#messageDropdown .messageDropdownBox').removeClass('show');
    });
    $('#dropdownMessageMenu').on('scroll', function(event){hideContextMenu();});
    $(window).on('scroll', function(event){hideContextMenu();});

    /* Adjust dropdown height when resize. */
    $(window).on('resize', function(event)
    {
        let maxHeight = $(window).height() - $('#header').height() - 5;
        $("#dropdownMessageMenu").css('height', maxHeight).css('max-height', maxHeight)
    });
});
</script>
