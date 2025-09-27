<?php
helper::import('../../control.php');

class myIm extends im
{
    public function chatWithAi($modelId, $assistantId, $userID = 0, $version = '', $device = 'desktop')
    {
        $user = $this->im->user->getByID($userID);
        $user->rights = $this->loadModel('user')->authorize($user->account);

        global $app;
        $app->user = $user;

        $chatGid = "$userID&ai-{$modelId}";

        $aiChatPermission = commonModel::hasPriv('ai', 'chat');
        if(!$aiChatPermission) return false;

        $chat = $this->im->chat->getByGid($chatGid, false, false);
        if(!$chat) return false;

        $aiModel = $this->loadModel('ai')->getLanguageModel($modelId);
        if(!$aiModel || $aiModel->enabled == 0) return false;

        $context = $this->im->getAiChatLatestContext($modelId, $userID);

        $context = array_map(function($message) use ($userID) {
            return (object)array(
                'role'    => $message->user == $userID ? 'user' : 'assistant',
                'content' => $message->content,
            );
        }, $context);

        if(!empty($assistantId))
        {
            $assistant = $this->ai->getAssistantById($assistantId);
            if($assistant)
            {
                array_unshift($context, (object)array(
                    'role'    => 'system',
                    'content' => $assistant->systemMessage,
                ));
            }
        }

        $messages = $this->ai->converse($modelId, $context);

        if(!$messages)
        {
            $statusOutput = new stdclass();
            $statusOutput->result = 'success';
            $statusOutput->method = 'chatwithai';
            $statusOutput->users  = array($userID);
            $statusOutput->data   = (object)array('status' => 'error', 'modelId' => $modelId);

            return $this->im->sendOutputGroup(array($statusOutput));
        }

        $replyMessage = new stdclass();
        $replyMessage->gid         = imModel::createGID();
        $replyMessage->cgid        = $chatGid;
        $replyMessage->user        = "ai-{$modelId}";
        $replyMessage->content     = end($messages);
        $replyMessage->type        = 'normal';
        $replyMessage->contentType = 'text';

        $sender = new stdclass();
        $sender->id          = 0;
        $sender->displayName = empty($aiModel->name) ? $this->lang->ai->models->typeList[$aiModel->type] : $aiModel->name;
        if(!empty($assistant))
        {
            $sender->displayName = $assistant->name;
            $sender->aiAssistantIcon = $assistant->icon;
        }

        $replyMessage->data = new stdclass();
        $replyMessage->data->sender = $sender;
        $replyMessage->data         = json_encode($replyMessage->data);

        $chatMessages = $this->im->messageCreate(array($replyMessage), $userID);

        $chatOutput = new stdclass();
        $chatOutput->result = 'success';
        $chatOutput->method = 'messagesend';
        $chatOutput->users  = array($userID);
        $chatOutput->data   = $chatMessages;

        $statusOutput = new stdclass();
        $statusOutput->result = 'success';
        $statusOutput->method = 'chatwithai';
        $statusOutput->users  = array($userID);
        $statusOutput->data   = (object)array('status' => 'success', 'modelId' => $modelId);

        return $this->im->sendOutputGroup(array($statusOutput, $chatOutput));
    }
}
