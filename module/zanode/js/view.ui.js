window.checkServiceStatus = function(){
    toggleLoading('#serviceContent', true);
    $.get($.createLink('zanode', 'ajaxGetServiceStatus', 'nodeID=' + nodeID), function(response)
    {
        var resultData = JSON.parse(response);
        var isSuccess = true

        for (var key in resultData.data)
        {
            if(key == "ZTF")
            {
                if(resultData.data[key] == 'ready')
                {
                    $('.dot-ztf').removeClass("text-danger")
                    $('.dot-ztf').addClass("text-success")
                }
                else{
                    $('.dot-ztf').removeClass("text-success")
                    $('.dot-ztf').addClass("text-danger")
                }

                if(resultData.data[key] == 'ready' || resultData.data[key] == 'not_available')
                {
                    $('.service-status').removeClass('hidden');
                    $('.ztf-status').text(zanodeLang.init[resultData.data[key]])
                    $('.ztf-install').text('');
                    $('.ztf-install-icon').hide();
                }
                else
                {
                    if(resultData.data[key] == 'unknown')
                    {
                        if(hostType == 'physics')
                        {
                            $('.ztf-status').text(zanodeLang.init.not_install)
                        }
                        else
                        {
                            $('.ztf-status').text(zanodeLang.init.unknown)
                        }
                    }
                    else
                    {
                        $('.ztf-status').text(zanodeLang.initializing)
                    }
                    $('.ztf-install').text(zanodeLang.install);
                }
            }
            else if(key == "node")
            {
                if(resultData.data[key] && zanodeLang.statusList[nodeStatus] != zanodeLang.statusList[resultData.data[key]])
                {
                    loadCurrentPage();
                }
            }
            else
            {
                if(resultData.data[key] == 'ready')
                {
                    $('.dot-zenagent').removeClass("text-danger")
                    $('.dot-zenagent').addClass("text-success")
                }
                else{
                    $('.dot-zenagent').removeClass("text-success")
                    $('.dot-zenagent').addClass("text-danger")
                }
                $('.zenagent-status').text(zanodeLang.init[resultData.data[key]])
                if(resultData.data[key] == 'ready')
                {
                    $('.node-init-install').removeClass('hidden');
                }
                else
                {
                    if(resultData.data[key] == 'unknown')
                    {
                        $('.ztf-zenagent').text(zanodeLang.init.unknown)
                    }
                    else
                    {
                        $('.zenagent-status').text(zanodeLang.initializing)
                    }
                }
            }
            if(resultData.data[key] !== 'ready' && key != 'node')
            {
                isSuccess = false
            }
        };

        if(!isSuccess)
        {
            $('.init-fail').removeClass('hidden');
            $('.init-success').addClass('hidden');
        }
        else
        {
            $('.init-success').removeClass('hidden');
            $('.init-fail').addClass('hidden');
        }

        setTimeout(function() {
            toggleLoading('#serviceContent', false);
            $(".service-status, .status-notice").removeClass('hidden')
        }, 500);
    });
}

function copyToClipboard(text) {
   var tempInput = document.createElement("input");
   document.body.appendChild(tempInput);
   tempInput.value = text;
   tempInput.select();
   document.execCommand("copy");
   document.body.removeChild(tempInput);
}

function sshCopy()
{
    copyToClipboard($('#ssh-copy').val());

    zui.Messager.show({
        type:    'success',
        content: zanodeLang.copied,
        time:    2000
    });
}

function pwdCopy()
{
    copyToClipboard($('#pwd-copy').val());

    zui.Messager.show({
        type:    'success',
        content: zanodeLang.copied,
        time:    2000
    });
}

function pwdShow()
{
    var pwd     = $('#pwd-copy').val();
    var pwdStar = "******".padEnd(pwd.length, '*')
    var html    = $('.btn-pwd-show').html()
    if(html.indexOf('icon-eye-off') != -1)
    {
        $('#pwd-text').text(pwdStar)
        $('.btn-pwd-show').html("<i class='icon-common-eye icon-eye' title='" + zanodeLang.showPwd +  "'></i>")
    }
    else
    {
        $('#pwd-text').text(pwd)
        $('.btn-pwd-show').html("<i class='icon-common-eye icon-eye-off' title='" + zanodeLang.hidePwd +  "'></i>")
    }

}

function onCopy()
{
    copyToClipboard($('#initBash').val());

    zui.Messager.show({
        type:    'success',
        content: zanodeLang.copied,
        time:    2000
    });
}

$(function()
{
    $('.create-snapshot i').replaceWith("<img src='static/svg/snapshot.svg' />");

    checkServiceStatus();
});

window.editSnapshot = function(url)
{
    openUrl(url, {load: 'modal'});
}
