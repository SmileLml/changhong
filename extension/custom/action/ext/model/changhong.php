<?php
/**
 * 创建一个操作记录。
 * Create a action.
 *
 * @param  string           $objectType
 * @param  int              $objectID
 * @param  string           $actionType
 * @param  string|bool      $comment
 * @param  string|int|float $extra        the extra info of this action, according to different modules and actions, can set different extra.
 * @param  string           $actor
 * @param  bool             $autoDelete
 * @access public
 * @return int|bool
*/
public function create($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $autoDelete = true)
{
    $actionID = parent::create($objectType, $objectID, $actionType, $comment, $extra, $actor, $autoDelete);

    $prompt = $this->loadModel('ai')->getPromptByModule();
    if(!empty($prompt))
    {
        $aiResponse = $this->ai->executePromptByModule($prompt, $objectID);
        if(is_int($aiResponse))
        {
            $aiError = sprintf($this->lang->ai->execute->failFormat, $this->lang->ai->execute->executeErrors["$aiResponse"]);
            $_SESSION['aiError'] = $aiError;

            $aiErrorAction = new stdclass();
            $aiErrorAction->objectType = $objectType;
            $aiErrorAction->objectID   = $objectID;
            $aiErrorAction->actor      = empty($actor) ? $this->app->user->account : $actor;
            $aiErrorAction->action     = 'aiscoredfail';
            $aiErrorAction->date       = helper::now();
            $aiErrorAction->extra      = $aiResponse;

            $this->dao->insert(TABLE_ACTION)->data($aiErrorAction)->exec();
        }
        elseif(is_string($aiResponse))
        {
            // TODO 异步
            $this->loadModel('aiscore')->saveAIScoreResult($prompt->id, $aiResponse, $objectType, $objectID, $actionType);
        }
    }

    return $actionID;
}
