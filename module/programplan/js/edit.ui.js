/**
 * 父阶段更改值操作。
 * Change parent stage.
 *
 * @param  stageID stageID
 * @return void
 */
function changeParentStage(event)
{
    const stageID = parseInt($(event.target).val());
    $('#acl').attr('disabled', stageID != 0);

    $.get($.createLink('programplan', 'ajaxGetStageAttr', 'stageID=' + stageID), function(attribute)
    {
        var isPicker = $('#attributeType').find('.picker-box').length > 0;
        if((attribute.length == 0 || attribute == 'mix') && !isPicker)
        {
            $('#attributeType').empty().append('<div class="form-group-wrapper picker-box" id="attribute"></div>');
            $('#attribute').picker({name: 'attribute', items: stageTypeItems, defaultValue: attribute});
        }
        if(isPicker && attribute != 'mix' && attribute.length > 0)
        {
            $('#attributeType').find('[name=attribute]').zui('picker').destroy();
            $('#attributeType').find('.picker-box').remove();
            $('#attributeType').append('<span>' + stageTypeList[attribute] + '</span>');
        }
    });
}

/**
 * 编辑阶段提交操作。
 * Submit form for edit stage.
 *
 * @return void
 */
window.editStage = function()
{
    let result        = true;
    let currentParent = $('[name=parent]').val();
    if(plan.parent != currentParent && currentParent != 0)
    {
        result = false;
        $.get($.createLink('programplan', 'ajaxGetStageAttr', 'stageID=' + currentParent), function(attribute)
        {
            if(attribute != 'mix' && plan.attribute != attribute)
            {
                zui.Modal.confirm(changeAttrLang.replace('%s', stageTypeList[attribute])).then((res) =>
                {
                    if(res) formSubmit();
                });
            }
            else
            {
                formSubmit();
            }
        });
    }

    var currentAttribute    = $('[name=attribute]').val();
    var hasChangedAttribute = (currentAttribute && currentAttribute != 'mix' && plan.attribute != currentAttribute);
    var hasChangedParent    = ((isTopStage && currentParent != 0) || (!isTopStage && plan.parent != currentParent));
    if(hasChangedAttribute && !hasChangedParent && !isLeafStage)
    {
        result = false;
        zui.Modal.confirm(changeAttrLang.replace('%s', stageTypeList[currentAttribute])).then((res) =>
        {
            if(res) formSubmit();
        });
    }

    return result;
}

/**
 * 提交表单操作。
 * Submit form.
 *
 * @access public
 * @return void
 */
function formSubmit()
{
    let $form    = $('#editForm form');
    let formUrl  = $form.attr('action');
    let formData = new FormData($form[0]);
    $.ajaxSubmit({
        url: formUrl,
        data: formData,
        onFail: (error) => {
            if(error?.message) showValidateMessage(error.message);
        }
    });
}
