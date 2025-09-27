/**
 * Change zanode type.
 *
 * @access public
 * @return void
 */
function onChangeType()
{
    if($('[name=hostType]').val() == 'physics')
    {
        $("[name^='parent']").closest('.form-row').hide();
        $("[name^='image']").closest('.form-group').hide();
        $("[name='cpuCores']").closest('.form-group').hide();
        $("[name='memory']").closest('.form-row').hide();
        $("[name='diskSize']").closest('.form-row').hide();
        $('#extranet').closest('.form-row').removeClass('hidden');
        $('#osName').closest('.form-row').addClass('hidden');
        $('#osNamePhysicsContainer').removeClass('hidden');
    }
    else
    {
        $("[name^='parent']").closest('.form-row').show();
        $("[name^='image']").closest('.form-group').show();
        $("[name='cpuCores']").closest('.form-group').show();
        $("[name='memory']").closest('.form-row').show();
        $("[name='diskSize']").closest('.form-row').show();
        $('#extranet').closest('.form-row').addClass('hidden');
        $('#osName').closest('.form-row').removeClass('hidden');
        $('#osNamePhysicsContainer').addClass('hidden');
    }
}

function onHostChange(host)
{
    const hostID = typeof host == 'object' ? $('[name=parent]').val() : host;
    var link = $.createLink('zanode', 'ajaxGetImages', 'hostID=' + hostID);
    $.get(link, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            $imagePicker = $("[name^='image']").zui('picker');
            $imagePicker.render({items: data});
            $imagePicker.$.clear();
            onImageChange();
        }
    });
}

function onImageChange()
{
    var image = $('[name=image]').val();
    var link  = $.createLink('zanode', 'ajaxGetImage', 'image=' + image);
    $.get(link, function(data)
    {
        data = JSON.parse(data);

        $('#osName').val(data.osName);
        if(data.memory != 0)
        {
            $('#memory').val(data.memory);
        }
        if(data.memory != 0)
        {
            $('#diskSize').val(data.disk);
        }
    });
}

/**
 * Load hosts.
 *
 * @access public
 * @return void
 */
window.loadHosts = function()
{
    var hostLink = $.createLink('zahost', 'ajaxGetHosts');
    $.get(hostLink, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);

            $hostPicker = $('#parent').zui('picker');
            $hostPicker.render({items: data});
            $hostPicker.$.clear();
        }
    });
}

function onChangeSystem(event)
{
    var osItems = [];
    if($(event.target).val() == 'linux')
    {
        for(var i in linuxList)
         {
            osItems.push({'text': linuxList[i], 'value': i});
        }
    }
    else
    {
        for(var i in windowsList)
        {
            osItems.push({'text': windowsList[i], 'value': i});
        }
    }

    $osPicker = zui.Picker.query('#osNamePhysics');
    $osPicker.render({items: osItems});
    $osPicker.$.clear();
}

$(function()
{
    if(hostID) onHostChange(hostID);
});
