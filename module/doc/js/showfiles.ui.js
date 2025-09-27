window.searchTitle = function()
{
    const searchTitle = $('#featureBar .searchBox input[name=title]').val();
    loadPage(searchLink.replace('%s', searchTitle));
}
$(document).on('keypress', '#featureBar .searchBox #title', function(e)
{
    if(e.keyCode == 13) loadPage(searchLink.replace('%s', $(this).val()));
});

window.renderCell = function(result, {col, row})
{
    if(col.name == 'title')
    {
        let titleHtml  = '';
        let titleClass = '';
        if(imageExtensionList.includes(row.data.extension))
        {
            titleClass = 'file-name';
            titleHtml += `<div style='display: inline-block'><img width='16' src='${row.data.webPath}' data-extension='${row.data.extension}' data-id='${row.data.id}' data-width='${row.data.imageWidth}'/></div>`;
        }
        else
        {
            titleHtml += row.data.fileIcon;
        }

        titleHtml += `<span class='ml-1 ${titleClass}'>${row.data.fileName}</span>`;

        result[0] = {html: titleHtml};
        return result;
    }

    if(col.name == 'objectID')
    {
        const sourceAttr = row.data.objectType != 'doc' ? " data-toggle='modal' data-size='lg'" : '';
        const objectLink = $.createLink(row.data.objectType == 'requirement' ? 'story' : row.data.objectType, 'view', `objectID=${row.data.objectID}`);
        const sourceHtml = `<span>${row.data.objectName}</span><a title='${row.data.sourceName}' href='${objectLink}' ${sourceAttr}> ${row.data.sourceName}</a>`;

        result[0] = {html: sourceHtml};
        return result;
    }

    if(col.name == 'size')
    {
        result[0] = {html: row.data.sizeText};
        return result;
    }

    return result;
}

window.downloadFile = function(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var windowWidth = $(window).width();

    var url = $.createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    url    += url.includes('?') ? '&' : '?';
    url    += `'${sessionString}'`;

    width = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    loadModal(url);

    return false;
}

$(document).off('click', '#actionBar .btn.export').on('click', '#actionBar .btn.export', function()
{
    const dtable = zui.DTable.query($('#table-doc-showfiles'));
    if(!$('#table-doc-showfiles').length) return;

    const checkedList = dtable.$.getChecks();
    $.cookie.set('checkedItem', checkedList, {expires:config.cookieLife, path:config.webRoot});
});
