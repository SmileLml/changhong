<?php
helper::importControl('ai');

class myAI extends ai
{
    public function ajaxGetScores($objectType, $objectID)
    {
        $fields   = $this->ai->getScoresFields($objectType);
        $aiScores = $this->loadModel('aiscore')->getResultByObject($objectType, $objectID, $fields);
        $this->aiscore->updateResultTableConfig($objectType, $fields);

        $this->view->aiScores = $aiScores;
        $this->view->fields   = $fields;
        $this->display();
    }
}
