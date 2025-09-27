window.exportChart = function()
{
    const fileName  = $('#fileName').val();
    const chartList = $('#staticScreen').contents().find('canvas');

    const form = new FormData();
    form.append('fileName', fileName || defaultFileName);

    chartList.each(function(_, canvas)
    {
        if(typeof(canvas.toDataURL) == 'undefined')
        {
            zui.Modal.alert(errorExportChart);
            return false;
        }

        form.append('charts[]', canvas.toDataURL("image/png"));
    })

    const url = $('#exportChartForm').attr('action');
    $.ajaxSubmit({url, data: form})
    zui.Modal.hide();
};
