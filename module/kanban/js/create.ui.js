window.clickCopyCard = function(event)
{
    setCopyKanban($(event.target).closest('.copy-card').data('id'));
    $('#copyKanbanModal').modal('hide');
}

window.setCopyKanban = function(kanbanID)
{
    const copyRegion = $('[name=copyRegionInfo]').prop('checked');
    const spaceType  = $('[name=type]:checked').val();
    const url = $.createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + spaceType + '&copyKanbanID=' + kanbanID + '&exyra=copyRegion=' + (copyRegion ? '1' : '0'));
    loadPartial(url, '#WIPCountBox, #spaceBox, #nameBox, #ownerBox, #teamBox, #fixedColBox, #autoColBox, #archiveBox, #manageProgressBox, #alignmentBox, #descBox, #whitelistBox', {success: function()
    {
        waitDom('#spaceBox [name=space]', function()
        {
            const copySpaceID = $('#spaceBox input[name=space]').val();
            $('#spaceBox input[name=space]').zui('picker').$.setValue(copySpaceID);
        });
    }});
}

window.toggleImportObjectBox = function(e)
{
    let isImport = $(e.target).val() == 'on';
    if(!isImport)
    {
        $("input[name^='importObjectList']").attr('disabled', 'disabled');
        $('#objectBox').hide();
    }
    else
    {
        $("input[name^='importObjectList']").removeAttr('disabled');
        $('#objectBox').show();
    }
}

window.waitDom('input[name=fluidBoard]', function()
{
    handleKanbanWidthAttr();
});
