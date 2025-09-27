/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
window.setRole = function(e, roleID)
{
    const account = $(e.target).val();
    const role    = roles[account];
    const $role   = $('#role' + roleID);
    $role.val(role);

    let members    = [];
    let $accounts  = $('#teamForm').find('.picker-box [name^=account]');
    $accounts.each(function()
    {
        let $account       = $(this);
        let account        = $account.val();
        let $accountPicker = $account.zui('picker');
        let accountItems   = $accountPicker.options.items;

        for(i = 0; i < $account.length; i++)
        {
            let value = $account.eq(i).val();
            if(value != '') members.push(value);
        }

        $.each(accountItems, function(i, item)
        {
            if(item.value == '') return;
            accountItems[i].disabled = members.includes(item.value) && item.value != account;
        })

        $accountPicker.render({items: accountItems});
    });
}

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/_i/g, itemIndex);
    const $currentTr = $(obj).closest('tr');
    $currentTr.after(item);

    const $newRow = $currentTr.next();
    $('select[name^=account]').each(function()
    {
        const selectValue = $(this).val();
        if(selectValue) $newRow.find(`option[value='${selectValue}']`).remove();
    });

    $('#teamForm .table tbody tr .actions-list .btn-link').eq(1).removeClass('hidden');

    itemIndex ++;
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.deleteItem = function(obj)
{
    if($('#teamForm .table tbody tr').length < 3) $('#teamForm .table tbody tr .actions-list .btn-link').eq(3).addClass('hidden');
    $(obj).closest('tr').remove();
}

/**
 * Set dept users.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.setDeptUsers = function(e)
{
    const dept = $(e.target).val(); // Get dept ID.
    const link = $.createLink('marketresearch', 'manageMembers', 'researchID=' + researchID + '&dept=' + dept); // Create manageMembers link.
    loadPage(link);
}
