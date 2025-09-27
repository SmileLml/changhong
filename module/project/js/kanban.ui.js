window.getCol = function(col)
{
    if(col.cards) col.subtitle = {html: "<span class='text-gray ml-1'>" + col.cards + "</span>"};
}

window.itemRender = function(info)
{
    info.item.className.push('card-item-' + (info.item.status == 'doing' && info.item.delay ? 'delay' : info.item.status));
}

window.getItem = function(info)
{
    if(info.item.delay)
    {
        info.item.suffix      = delayed;
        info.item.suffixClass = 'label danger rounded-xl' + (info.item.status == 'doing' ? ' mr-8' : '');
    }
    if(info.item.status == 'doing') info.item.prefix = {component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}};
    if(info.item.cardType == 'execution' && privs.canViewExecution)
    {
        info.item.titleUrl = $.createLink('execution', 'task', `id=${info.item.id}`);
    }
    else if(info.item.cardType == 'project' && privs.canViewProject)
    {
        info.item.titleUrl = $.createLink('project', 'index', `id=${info.item.id}`);
    }
    info.item.titleAttrs = {'class': 'text-black clip', 'title' : info.item.title};
}

window.canDrop = function(dragInfo, dropInfo)
{
    if(!dragInfo) return false;

    const column = this.getCol(dropInfo.col);
    const lane   = this.getLane(dropInfo.lane);
    if(!column || !lane) return false;

    if(dropInfo.type == 'item') return false;
    if(dragInfo.item.lane != lane.name)   return false;
    if(dragInfo.item.cardType == 'execution') return false;
    if(dragInfo.item.status == 'wait'   && dropInfo.col == 'doingProjects')  return privs.canStartProject;
    if(dragInfo.item.status == 'wait'   && dropInfo.col == 'closed')         return privs.canCloseProject;
    if(dragInfo.item.status == 'doing'  && dropInfo.col == 'closed')         return privs.canCloseProject;
    if(dragInfo.item.status == 'closed' && dropInfo.col == 'doingProjects')  return privs.canActivateProject;
    return false;
}

window.onDrop = function(changes, dropInfo)
{
    const item  = dropInfo['drag']['item'];
    const toCol = dropInfo['drop']['col'];

    if(item.status == 'wait')   methodName = toCol == 'doingProjects' ? 'start' : 'close';
    if(item.status == 'doing')  methodName = 'close';
    if(item.status == 'closed') methodName = 'activate';
    zui.Modal.open({url: $.createLink('project', methodName, 'projectID=' + item.id), size: 'lg'});
    return false;
}
