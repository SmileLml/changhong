<style>
.priWidth{width:30px !important;}
.dateWidth{width:80px !important; padding: 8px !important;}
.delayWidth{width:80px !important;}
.estWidth{width:65px !important; padding: 8px !important}
.taskConsumedWidth{width:85px !important;}
.taskTotalWidth{width:65px !important; padding: 8px !important;}
.projectConsumedWidth{width:80px !important; padding: 8px !important;}
.userConsumedWidth{width:80px !important; padding: 8px !important;}
.ai-score-bug-table {overflow-x: auto !important; -webkit-overflow-scrolling: touch !important;}
#aiScoreBugTable {min-width: 1600px; table-layout: fixed;}
#aiScoreBugTable th, #aiScoreBugTable td {min-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
</style>
<div class='flex bg-canvas p-2 gap-3' id='conditions'>
  <div class='input-group w-1/2'>
    <span class='input-group-addon'><?php echo $lang->pivot->aiScoreSearchDate;?></span>
    <div id='beginPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $begin;?>', onChange: (e) => changeParams()}" ></div>
    <span class='input-group-addon'><?php echo $lang->pivot->aiScoreSearchDateTo;?></span>
    <div id='endPicker' zui-create zui-create-datepicker="{defaultValue: '<?php echo $end;?>', onChange: (e) => changeParams()}"></div>
  </div>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->aiScoreSearchTitle;?></span>
    <?php echo html::input('title', $searchTitle, "class='form-control' onchange='changeParams()'");?>
  </div>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->aiScoreSearchDept;?></span>
    <?php echo html::select('dept', $depts, $searchDept, "class='form-control chosen' onchange='changeParams()'");?>
  </div>
  <div class='input-group w-1/4'>
    <span class='input-group-addon'><?php echo $lang->pivot->aiScoreSearchUser;?></span>
    <?php echo html::select('user', $users, $searchUser, "class='form-control chosen' onchange='changeParams()'");?>
  </div>
</div>
<?php if(empty($bugs) || empty($fields)):?>
<div class="cell bg-canvas">
  <div class="dtable-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<?php global $config;?>
<div class='cell'>
  <div class='panel rounded ring-0 bg-canvas'>
    <div class="panel-heading">
      <div class="panel-title"><?php echo $title;?></div>
    </div>
    <div class='panel-body pt-0'>
      <div class='table-responsive ai-score-bug-table' data-ride='table'>
        <table class='table table-condensed table-striped table-bordered table-fixed' id="aiScoreBugTable">
          <thead>
            <tr class='colhead text-center bg-canvas'>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->dept;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->realname;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->project;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->ID;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->title;?></th>
              <th class="border <?php echo 'w-'.(count($fields) * 20);?>" colspan="<?php echo count($fields); ?>"><?php echo $lang->pivot->aiScoreBugFields->scoreField;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->remarkAvgScore;?></th>
              <th class="border w-30" rowspan="2"><?php echo $lang->pivot->aiScoreBugFields->scoreTime;?></th>
            </tr>
            <tr class='colhead text-center bg-canvas'>
              <?php foreach($fields as $filed): ?>
              <th class="border w-20"><?php echo $lang->bug->$filed;?></th>
							<?php endforeach;?>
            </tr>
					</thead>
          <tbody>
            <?php foreach($bugs as $bug):?>
            <tr class='text-center'>
              <td class='border'><?php echo zget($depts, $bug->accountDept, '');?></td>
              <td class='border'><?php echo $bug->accountName;?></td>
              <td class='border'><?php echo $bug->projectName;?></td>
              <td class='border'><?php echo $bug->objectID;?></td>
              <td class='border'><?php echo html::a(helper::createLink('bug', 'view', "bugID=$bug->objectID"), $bug->title);?></td>
							<?php foreach($fields as $filed): ?>
              <td class='border'><?php echo isset($bug->$filed) ? $bug->$filed : '-';?></td>
							<?php endforeach;?>
              <td class='border'><?php echo $bug->remarkScore;?></td>
              <td class='border'><?php echo $bug->createDate;?></td>
            </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <div class='pull-right p-2 no-morph' id='pagerWorkAssignSummary' zui-create zui-create-pager="window.pagerWorkAssignSummaryOptions"></div>
  </div>
</div>
<?php endif;?>
<script>
function changeParams()
{
	const beginPick = $('#beginPicker').zui();
	const endPick 	= $('#endPicker').zui();

	var begin 		  = beginPick.$.value;
	var end   		  = endPick.$.value;
	var searchDept  = $('#conditions').find('#dept').val();
	var searchUser  = $('#conditions').find('#user').val();
	var searchTitle = $('#conditions').find('#title').val();
	if(begin.indexOf('-') != -1)
	{
		var beginarray = begin.split("-");
		var begin 		 = '';
		for(i=0; i < beginarray.length; i++)
		{
			begin = begin + beginarray[i];
		}
	}
	if(end.indexOf('-') != -1)
	{
		var endarray = end.split("-");
		var end 		 = '';
		for(i=0; i < endarray.length; i++)
		{
			end = end + endarray[i];
		}
	}

	var params = btoa(unescape(encodeURIComponent('begin=' + begin + '&end=' + end + '&searchDept=' + searchDept + '&searchUser=' + searchUser + '&searchTitle=' + searchTitle)));
	var link   = $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID?> + '&group=' + <?php echo $groupID;?> + '&method=aiscorebug&params=' + params);
	window.location.href = link;
}

function getLink(info)
{
	const beginPick = $('#beginPicker').zui();
	const endPick   = $('#endPicker').zui();

	var begin 		  = beginPick.$.value;
	var end   		  = endPick.$.value;
	var searchTitle = $('#conditions').find('#title').val();
	var searchDept  = $('#conditions').find('#dept').val();
	var searchUser  = $('#conditions').find('#user').val();

    if(begin.indexOf('-') != -1)
    {
			var beginarray = begin.split("-");
			var begin 		 = '';
			for(i=0; i < beginarray.length; i++)
			{
				begin = begin + beginarray[i];
			}
    }
    if(end.indexOf('-') != -1)
    {
			var endarray = end.split("-");
			var end 		 = '';
			for(i=0; i < endarray.length; i++)
			{
				end = end + endarray[i];
			}
    }

    var params = btoa(unescape(encodeURIComponent('begin=' + begin + '&end=' + end + '&searchDept=' + searchDept + '&searchUser=' + searchUser + '&searchTitle=' + searchTitle + '&recTotal=' + info.recTotal + '&recPerPage=' + info.recPerPage + '&pageID=' + info.page)));
		return $.createLink('pivot', 'preview', 'dimension=' + <?php echo $dimensionID?> + '&group=' + <?php echo $groupID;?> + '&method=aiscorebug&params=' + params);
}

window.pagerWorkAssignSummaryOptions = {
	items: [
		{type: 'info', text: '<?php echo str_replace('<strong>{recTotal}</strong>', $pager->recTotal, $lang->pager->totalCount);?>'},
		{type: 'size-menu', text: '<?php echo str_replace('<strong>{recPerPage}</strong>', $pager->recPerPage , $lang->pager->pageSize);?>', dropdown: {placement: 'top'}},
		{type: 'link', page: 'first', icon: 'icon-first-page', hint: '<?php echo $lang->pager->firstPage;?>'},
		{type: 'link', page: 'prev', icon: 'icon-angle-left', hint: '<?php echo $lang->pager->previousPage;?>'},
		{type: 'info', text: '<?php echo $pager->pageID;?>/<?php echo $pager->pageTotal;?>'},
		{type: 'link', page: 'next', icon: 'icon-angle-right', hint: '<?php echo $lang->pager->nextPage;?>'},
		{type: 'link', page: 'last', icon: 'icon-last-page', hint: '<?php echo $lang->pager->lastPage;?>'},
	],
	page: <?php echo $pager->pageID;?>,
	recTotal: <?php echo $pager->recTotal;?>,
	recPerPage: <?php echo $pager->recPerPage;?>,
	linkCreator: (info) => {return getLink(info);}
};
</script>