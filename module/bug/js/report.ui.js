window.selectAll = function(e)
{
    let allChecked = true;
    $('input[name=charts]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('input[name=charts]').each(function()
    {
        $(this).prop('checked', !allChecked);
    });
};

window.clickInit = function(e)
{
    initReport();
};

window.handleShowReportTab = (event) =>
{
    $(event.target).find('[data-zui-echarts]').each(function()
    {
        const echart = $(this).zui();
        if(echart) echart.chart.resize();
    });
};

function initReport()
{
    const chartType = $('a[data-toggle=tab].active').data('param');
    const form      = new FormData();
    $('input[name=charts]').each(function()
    {
        if($(this).prop('checked')) form.append('charts[]', $(this).val());
    })
    postAndLoadPage($.createLink('bug', 'report', params + '&chartType=' + chartType), form, '#report');
}
