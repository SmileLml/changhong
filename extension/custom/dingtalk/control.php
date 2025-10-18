<?php
class dingtalk extends control
{
    /**
     * Display the dingtalk config page.
     *
     * @return void
     */
    public function config()
    {
        if($_POST)
        {
            $this->dingtalk->saveConfig();

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $dingtalkConfig = $this->dingtalk->getConfig();

        $this->view->title          = $this->lang->dingtalk->common;
        $this->view->dingtalkConfig = $dingtalkConfig;

        $this->display();
    }

    public function createTaskWord()
    {
        $taskId = isset($_GET['taskId']) ? (int)$_GET['taskId'] : 1;
        
        $result = $this->dingtalk->createTaskWord($taskId);
        
        if($result) {
            // Word文档会直接输出并下载，不需要返回success
        } else {
            echo '任务不存在或生成失败';
        }
    }

    public function createBugWord()
    {
        $this->dingtalk->createBugWord();
        
        return 'success';
    }

    public function createStoryWord()
    {
        $storyID = isset($_GET['storyID']) ? (int)$_GET['storyID'] : 1;
        
        $result = $this->dingtalk->createStoryWord($storyID);
        
        if($result) {
            // Word文档会直接输出并下载，不需要返回success
        } else {
            echo '故事不存在或生成失败';
        }
    }
}