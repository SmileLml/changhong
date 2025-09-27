function redirectSetting(event)
{
    const $target = $(event.target);
    if($target.hasClass('icon-help')) return false;

    const $box = $target.closest('.setting-box');
    if($box.length == 0) return false;

    if(!$box.hasClass('disabled') && $box.data('url') != undefined) loadPage($box.data('url'));
}

$(function()
{
    /* Update patch, plugin, news, mooc from zentao.net. */
    if(isAdminUser && hasInternet) $.get($.createLink('admin', 'ajaxSetZentaoData'));
});
