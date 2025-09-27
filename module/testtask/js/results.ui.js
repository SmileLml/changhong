$(function()
{
    window.waitDom('#casesResults .result-item', function(){ $('#casesResults .result-item').first().trigger('click');})
});

/**
 * Set height of the file modal.
 *
 * @access public
 * @return void
 */
window.setFileModalHeight = function()
{
    $($(this).attr('href')).find('.modal-body').css('max-height', ($(this).closest('.modal-content').height() - 35) + 'px');
}
