/**
 * 预览选中的图表。
 * Preview the selected charts.
 *
 * @access public
 * @return void
 */
function previewCharts()
{
    const checkedList = $('#moduleMenu menu').zui('tree').$.getChecks();

    const checkedCharts = [];
    checkedList.forEach((itemKey, index) => {
        if(itemKey.includes(':') && itemKey.includes('_'))
        {
            const keys = itemKey.split(':')[1].split('_');
            checkedCharts.push({index, keys});
        }
    });

    if(checkedCharts.length == 0) return;
    if(checkedCharts.length > maxPreviewCount)
    {
        zui.Modal.alert(maxPreviewTips);
        return false;
    }

    const form = new FormData();
    checkedCharts.forEach(chart => {
      const {index, keys} = chart;
      form.append('charts[' + index + '][groupID]', keys[0]);
      form.append('charts[' + index + '][chartID]', keys[1]);
    });

    postAndLoadPage(previewUrl, form, '#chartPanel');
}

/**
 * 筛选一个图表。
 * Filter a chart.
 *
 * @param  string chartID
 * @access public
 * @return bool|void
 */
loadChart = function(chartID)
{
    if(!chartID.includes('_')) return false;

    const keys = chartID.split('_');
    const form = new FormData();
    form.append('groupID', keys[0]);
    form.append('chartID', keys[1]);

    $('#filter_' + chartID + ' .filter').each(function(index)
    {
        const $filter = $(this);
        if ($filter.hasClass('filter-input'))
        {
            form.append('filterValues[' + index + ']', $filter.find('input').val());
        }
        else if($filter.hasClass('filter-select'))
        {
            const value = $filter.find('.pick-value').val();
            if(Array.isArray(value))
            {
                value.filter(Boolean).forEach((item) => form.append('filterValues[' + index + '][]', item));
            }
            else
            {
                form.append('filterValues[' + index + ']', value);
            }
        }
        else if($filter.hasClass('filter-date') || $filter.hasClass('filter-datetime'))
        {
            const $pickValue = $filter.find('.pick-value');
            if($pickValue.length == 1)
            {
                form.append('filterValues[' + index + ']', $pickValue.val());
            }
            else if($pickValue.length == 2)
            {
                form.append('filterValues[' + index + '][begin]', $pickValue.eq(0).val());
                form.append('filterValues[' + index + '][end]',  $pickValue.eq(1).val());
            }
        }
    });

    postAndLoadPage(previewUrl, form, '#chart_' + chartID);
}
