<?php
class sourceModel extends model
{
    /**
     * Get rules list.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getRulesList($objectType)
    {
        return $this->dao->select('*')->from(TABLE_SOURCE_RULES)
            ->where('objectType')->eq($objectType)
            ->orderBy('id_desc')
            ->fetchAll('field');
    }

    /**
     * Get weight pairs.
     *
     * @param  int    $promptID
     * @access public
     * @return array
     */
    public function getWeightPairs($promptID)
    {
        return $this->dao->select('field,weight')->from(TABLE_SOURCE_WEIGHT)
            ->where('promptID')->eq($promptID)
            ->orderBy('id_desc')
            ->fetchPairs();
    }
}