$(document).off('click', '#actionBar #' + exportMethod).on('click', '#actionBar #' + exportMethod, function()
{
    const dtable = zui.DTable.query($('#mainContent .dtable'));
    if(!$('#mainContent .dtable').length) return;

    const checkedList = dtable.$.getChecks();
    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});

window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    const url  = $.createLink('doc', 'sortDoc');
    const form = new FormData();
    form.append('orders', JSON.stringify(this.state.rowOrders));
    $.ajaxSubmit({url, data: form});
    return true;
}
