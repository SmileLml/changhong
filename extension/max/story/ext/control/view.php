<?php
helper::importControl('story');
class myStory extends story
{
    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param     executionID|projectID
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function view($storyID, $version = 0, $param = 0, $storyType = 'story')
    {
        /* Remove importToLib button in the modal. */
        if(isInModal()) $this->config->story->actions->view['mainActions'] = array_diff($this->config->story->actions->view['mainActions'], array('importToLib'));

        $this->view->approvers   = $this->loadModel('assetlib')->getApproveUsers('story');
        $this->view->libs        = $this->assetlib->getPairs('story');
        $this->view->reportPairs = $this->loadModel('researchreport')->getPairs();
        parent::view($storyID, $version, $param, $storyType);
    }
}
