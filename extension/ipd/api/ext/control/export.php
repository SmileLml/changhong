<?php
class api extends control
{
    /**
     * @param int $libID
     * @param int $version
     * @param int $release
     * @param int $moduleID
     * @param int $apiID
     */
    public function export($libID, $version = 0, $release = 0, $moduleID = 0, $apiID = 0)
    {
        if($release)
        {
            $rel = $this->api->getRelease(0, 'byId', $release);
            if(empty($rel)) $release = 0;
        }
        $lib = $this->loadModel('doc')->getLibById($libID);
        if(empty($lib)) return $this->locate($this->createLink('api', 'createLib'));

        if($apiID)
        {
            $api = $this->api->getByID($apiID, $version);

            $_POST['fileName'] = $api->title;
            $_POST['version']  = $api->version;
            $_POST['format']   = 'doc';
            $_POST['range']    = 'current';
        }
        if($_POST)
        {
            $range = $this->post->range;
            $this->post->set('module', $moduleID);
            if($range == 'productAll')
            {
                $this->post->set('productID', $lib->product);
                $this->post->set('module', 0);
            }
            if($range == 'projectAll')
            {
                $this->post->set('projectID', $lib->project);
                $this->post->set('module', 0);
            }

            $this->post->set('docID', $apiID);
            $this->post->set('release', $release);
            $this->post->set('version', $version);
            $this->post->set('libID', $libID);
            $this->post->set('range', $range);
            $this->post->set('kind', 'api');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);
            return $this->fetch('file', 'doc2Word', $_POST);
        }

        $this->app->loadLang('file');

        $this->view->title    = $this->lang->export;
        $this->view->fileName = zget($lib, 'name', '');
        $this->view->data     = $lib;
        $this->view->libID    = $libID;
        $this->view->version  = $version;
        $this->view->release  = $release;

        $this->view->chapters = array();
        $this->view->chapters['listAll'] = $this->lang->api->exportListAll;
        if(empty($release) and empty($version) and $this->session->spaceType == 'api')
        {
            if($lib->product)
            {
                $this->view->chapters['productAll'] = $this->lang->api->exportProductAll;
            }
            elseif($lib->project)
            {
                $this->view->chapters['projectAll'] = $this->lang->api->exportProjectAll;
            }
            else
            {
                $this->view->chapters['noLinkAll'] = $this->lang->api->exportNoLinkAll;
            }
        }

        $this->display();
    }
}
