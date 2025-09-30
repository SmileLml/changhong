<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->requestlog->search;?></a>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=requestlog'); ?>" data-module="requestlog"></div>
    <form class="main-table" data-ride="table" method="post" id="requestForm">
      <table class="table has-sort-head">
        <thead>
          <tr>
            <th class='w-40px'><?php echo $lang->requestlog->id;?></th>
            <th class='w-100px'><?php echo $lang->requestlog->url;?></th>
            <th class='w-60px'><?php echo $lang->requestlog->purpose;?></th>
            <th class='w-60px'><?php echo $lang->requestlog->requestType;?></th>
            <th class='w-40px'><?php echo $lang->requestlog->status;?></th>
            <th class='w-30px'><?php echo $lang->requestlog->params;?></th>
            <th class='w-30px'><?php echo $lang->requestlog->response;?></th>
            <th class='w-60px'><?php echo $lang->requestlog->requestTime;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($logList as $log):?>
          <tr>
            <td><?php echo $log->id;?></td>
            <td class="text-ellipsis" title="<?php echo $log->url;?>"><?php echo $log->url;?></td>
            <td><?php echo zget($lang->requestlog->purposeList, $log->purpose, $log->purpose);?></td>
            <td><?php echo $log->requestType;?></td>
            <td><?php echo zget($lang->requestlog->statusList, $log->status, $log->status);?></td>
            <td><?php echo html::commonButton($lang->requestlog->details, 'data-type="ajax" data-title="' . $lang->requestlog->params . '" data-remote="' . $this->createLink('requestlog', 'ajaxGetParams', 'id=' . $log->id) . '" data-toggle="modal"', 'btn btn-mini btn-primary triggerButton');?></td>
            <td><?php echo html::commonButton($lang->requestlog->details, 'data-type="ajax" data-title="' . $lang->requestlog->response . '" data-remote="' . $this->createLink('requestlog', 'ajaxGetResponse', 'id=' . $log->id) . '" data-toggle="modal"', 'btn btn-mini btn-primary triggerButton');?></td>
            <td><?php echo $log->requestTime;?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </form>
    <div class='table-footer'><?php echo $pager->show('right', 'pagerjs');?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
