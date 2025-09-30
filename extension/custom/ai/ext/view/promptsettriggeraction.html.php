<?php
/**
 * The ai prompt target form select view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>

<style>
  .center-wrapper {display: flex; justify-content: center; height: 100%;}
  .center-content {width: 100%; height: 100%; display: flex; flex-direction: column;}
  #select-form {display: flex; flex-direction: row; max-height: calc(100% - 32px);}
  #select-form > div {flex-grow: 1; flex-basis: 0; padding: 0px 12px;}
  .content-row {display: flex; flex-direction: row; padding: 8px 0px;}
  .input-label {width: 120px; padding: 6px 12px; text-align: right;}
  .input {flex-grow: 1;}
  #prompt-preview-wrapper {margin: 16px 0; height: calc(100% - 48px);}
  #prompt-preview {padding: 8px; border: 1px solid #E6EAF1; border-radius: 4px; background-color: #f8f8f8; min-height: 100px; height: 100%; overflow-y: auto; cursor: default; user-select: none;}
  #prompt-preview .active {background-color: #d6e5fe;}
  #prompt-preview .prompt-data, #prompt-preview .prompt-role, #prompt-preview .prompt-text {border-bottom: 1px solid #E6EAF1; padding: 16px 0;}
  #prompt-preview .prompt-data {padding-top: 0;}
  #prompt-preview .prompt-text {border-bottom: unset; padding-bottom: 0;}
  #prompt-preview .block-header {padding-bottom: 8px;}
  #prompt-preview .block-content > div + div {margin-top: 4px;}
  #prompt-preview .prompt-text-part + .prompt-text-part {margin-top: 4px;}
  #prompt-previewer {font-weight: bold;}
  #form-selector .header > * {display: inline-block;}
  #form-selector .content {margin: 6px 0; max-height: calc(100% - 47px); overflow-y: auto; border: 1px solid #E6EAF1; border-left: none;}
  .target-form-group {display: grid; grid-template-columns: 120px 1fr; grid-gap: 8px; border: 1px solid #E6EAF1; border-right: 0;}
  .target-form-group:first-of-type {border-top: none;}
  .target-form-group:last-of-type {border-bottom: none;}
  .target-form-group + .target-form-group {border-top: unset;}
  .target-form-group .header {display: flex; align-items: center; padding: 0 12px; background-color: #f8f8f8;}
  .target-form-group .options {display: grid; padding: 12px 16px; grid-template-columns: repeat(4, 1fr);}
  .target-form-group .option {padding: 4px 0;}
  .target-form-group .option label {cursor: pointer;}
  .target-form-group .option input {cursor: pointer;}
  #go-test-btn {margin-left: 16px;}

  @media (max-width: 1366px)
  {
    .target-form-group .options {grid-template-columns: repeat(3, 1fr);}
  }
</style>

<?php include $app->getExtensionRoot() . $config->edition . DS . $app->rawModule . DS . 'view/promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form id="mainForm" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div id='select-form'>
          <div id='form-selector'>
            <div class='header'>
              <h4><?php echo $lang->ai->prompts->setTriggerAction;?></h4>
              <small class='text-gray'><?php echo $lang->ai->prompts->setTriggerActionTip;?></small>
            </div>
            <div class='content'>
              <?php foreach ($config->ai->triggerAction as $name => $triggerActions):?>
                <div class='target-form-group'>
                  <div class='header text-gray'>
                    <div><?php echo $lang->ai->triggerAction[$name]['common'];?></div>
                  </div>
                  <div class='options'>
                    <?php foreach(array_keys($triggerActions) as $triggerAction):?>
                      <div class='option'>
                        <input type='checkbox' name='triggerAction[]' value='<?php echo "$name.$triggerAction";?>' <?php echo strrpos($prompt->triggerControl, ",$name.$triggerAction,") !== false ? 'checked' : '';?>/>
                        <label><?php echo $lang->ai->triggerAction[$name][$triggerAction];?></label>
                      </div>
                    <?php endforeach;?>
                  </div>
                </div>
              <?php endforeach;?>
            </div>
          </div>
          <div>
            <h4><?php echo $lang->ai->prompts->inputPreview;?></h4>
            <div id='prompt-preview-wrapper'>
              <div id='prompt-preview'>
                <div class='prompt-data'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->dataPreview;?></div>
                  <div class='block-content code' style='white-space: pre-wrap; word-break: break-word;'><?php echo $dataPreview;?></div>
                </div>
                <div class='prompt-role'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->rolePreview;?></div>
                  <div class='block-content'>
                    <div><?php echo $prompt->role;?></div>
                    <div><?php echo $prompt->characterization;?></div>
                  </div>
                </div>
                <div class='prompt-text'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->promptPreview;?></div>
                  <div class='block-content'>
                    <div><?php echo $prompt->purpose;?></div>
                    <div><?php echo $prompt->elaboration;?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <div style='display: flex; justify-content: center;'>
            <?php echo html::submitButton($lang->ai->nextStep, 'name="jumpToNext" value="1"');?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
$(function()
{
  $('.target-form-group .option label').click(function()
  {
    $(this).parent().find('input').click();
  });
});
</script>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
