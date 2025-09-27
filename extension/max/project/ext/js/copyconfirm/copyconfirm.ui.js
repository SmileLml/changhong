window.changeProduct = function(e)
{
    const oldProductID = e.item.oldProductID;
    const productID    = e.key;

    if(productID == 0)
    {
        $(`#dropdownToggle${oldProductID}`).closest('tbody').find(`tr.product-${oldProductID}`).addClass('hidden');
        $(`#dropdownToggle${oldProductID}`).closest('tr').find(`input[name="productIdList[]"]`).val(0);
    }
    else
    {
        $(`#dropdownToggle${oldProductID}`).closest('tbody').find(`tr.product-${oldProductID}`).removeClass('hidden');
        $(`#dropdownToggle${oldProductID}`).closest('tr').find(`input[name="productIdList[]"]`).val(productID);
    }

    $('#dropdownToggle' + oldProductID + ' .text').text(e.item.text);
}

window.renderRowData = function($row, index, row)
{
    if(row.grade > 1) $row.find('td[data-name="name"]').css('padding-left', `${row.grade * 10}px`); // 子阶段缩进
    $row.attr('data-parent', row.parent);
    $row.attr('data-id',     row.id);

    if(['waterfall', 'waterfallplus', 'ipd'].includes(projectModel))
    {
        if(row.stageBy == 'product' && row.isFirst == 1 && !isTpl)
        {
            const currentProduct = JSON.parse(productPairs)[row.productID];
            const productItems   = [];
            productItems.push({text: currentProduct, key: row.productID, oldProductID: row.productID});
            productItems.push({text: notCopyStage, key: 0, oldProductID: row.productID});

            chosenProductStage = chosenProductStage.replace('%s', currentProduct);

            $row.before(`<tr>
                <td colspan='4'><span class="text-sm text-secondary-500">${chosenProductStage}</span><button class="gray-300-outline size-sm rounded-full ml-2 btn" type="button" id="dropdownToggle${row.productID}"><span class="text">${currentProduct}</span><span class="caret"></span></button></td>
                <input type="hidden" name="productIdList[]" value="${row.productID}">
            </tr>`);

            new zui.Dropdown(`#dropdownToggle${row.productID}`, {
                menu: {
                    className: 'change-product-menu',
                    items: productItems,
                    onClickItem: changeProduct
                }
            });
        }

        $row.addClass(`product-${row.productID}`);

        $row.find('[data-name="id"] input').attr('name', `executionIDList[${row.productID}][${row.id}]`);
        $row.find('[data-name="parent"] input').attr('name', `parent[${row.productID}][${row.id}]`);
        $row.find('[data-name="name"] input').attr('name', `name[${row.productID}][${row.id}]`);
        $row.find('[data-name="begin"] input').attr('name', `begin[${row.productID}][${row.id}]`);
        $row.find('[data-name="end"] input').attr('name', `end[${row.productID}][${row.id}]`);
        $row.find('[data-name="percent"] input').attr('name', `percent[${row.productID}][${row.id}]`);
        $row.find('[data-name="milestone"]').find('input').attr('name', `milestone[${row.productID}][${row.id}]`);

        $row.find('[data-name="begin"]').find('.form-group-wrapper').on('inited', function(e, info)
        {
            info[0].render({name: `begin[${row.productID}][${row.id}]`});
            info[0].$.setValue(row.begin);
        });

        $row.find('[data-name="end"]').find('.form-group-wrapper').on('inited', function(e, info)
        {
            info[0].render({name: `end[${row.productID}][${row.id}]`});
            info[0].$.setValue(row.end);
        });

        $row.find('[data-name="PM"]').find('.picker-box').on('inited', function(e, info)
        {
            info[0].render({name: `PM[${row.productID}][${row.id}]`});
            info[0].$.setValue(row.PM);
        });

        $row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info)
        {
            const disabled = row.parentAttr != 'mix' && row.grade > 1 ? true : false;
            info[0].render({name: `attribute[${row.productID}][${row.id}]`, disabled: disabled});
            info[0].$.setValue(row.attribute);
        });

        $row.find('[data-name="acl"]').find('.picker-box').on('inited', function(e, info)
        {
            info[0].render({name: `acl[${row.productID}][${row.id}]`});
            info[0].$.setValue(row.acl);
        });

        /* 看板和迭代类型不支持百分比。*/
        if(row.type == 'kanban' || row.type == 'sprint')
        {
            $row.find('[data-name="percent"]').attr('readonly', true);
        }
    }
    else
    {
        $row.find('[data-name="id"] input').attr('name', `executionIDList[]`);
    }
}

/**
 * Compute work days.
 *
 * @access public
 * @return void
 */
window.computeWorkDays = function(currentID)
{
    isBatchEdit = false;
    if(currentID && typeof currentID != 'object')
    {
        index = currentID.replace(/[a-zA-Z]*\[|\]/g, '');
        if(!isNaN(index)) isBatchEdit = true;
    }

    let beginDate, endDate;
    if(isBatchEdit)
    {
        beginDate = $("input[name=begin\\[" + index + "\\]]").val();
        endDate   = $("input[name=end\\[" + index + "\\]]").val();
    }
    else
    {
        beginDate = $('input[name=begin]').val();
        endDate   = $('input[name=end]').val();
    }

    if(beginDate && endDate)
    {
        if(isBatchEdit)  $("input[name=days\\[" + index + "\\]]").val(computeDaysDelta(beginDate, endDate));
        if(!isBatchEdit) $('[name=days]').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val())
    {
        computeEndDate();
    }
}

/**
 * Compute the end date for project.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
function computeEndDate()
{
    let delta     = $('input[name^=delta]:checked').val();
    let beginDate = $('input[name=begin]').val();
    if(!beginDate) return;

    delta     = currentDelta = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    let endDate = formatDate(beginDate, delta - 1);

    $('input[name=end]').zui('datePicker').$.setValue(endDate);
    computeWorkDays();
    setTimeout(function(){$('[name=delta]').val(`${currentDelta}`)}, 0);
}

/**
 * 给指定日期加上具体天数，并返回格式化后的日期.
 *
 * @param  string dateString
 * @param  int    days
 * @access public
 * @return date
 */
function formatDate(dateString, days)
{
  const date = new Date(dateString);
  date.setDate(date.getDate() + days);

  const year  = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day   = String(date.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * Compute delta of two days.
 *
 * @param  string $date1
 * @param  string $date2
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    let weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

window.changeAttribute = function(e)
{
    const attribute    = e.target.value;
    const currentRowID = $(e.target).closest('tr').attr('data-id');

    if(attribute == 'mix')
    {
        $('tr[data-parent="' + currentRowID + '"]').each(function()
        {
            $(this).find('[name^="attribute"]').zui('picker').render({disabled: false});
        });
    }
    else
    {
        $('tr[data-parent="' + currentRowID + '"]').each(function()
        {
            $(this).find('[name^="attribute"]').zui('picker').render({disabled: true});
            $(this).find('[name^="attribute"]').zui('picker').$.setValue(attribute);
        });
    }
}
