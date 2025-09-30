<?php
/**
 * Get products by account.
 *
 * @param  int        $programID
 * @param  string     $status
 * @param  bool       $isAdmin
 * @param  array      $productIDList
 * @param  string     $account
 * @param  string|int $shadow       all | 0 | 1
 * @access public
 * @return array
 */
public function getListByAccount($programID = 0, $status = 'all', $isAdmin = false, $productIDList = array(), $account = '', $shadow = 0)
{
    $products = $this->dao->select('t1.*')->from(TABLE_PRODUCT)->alias('t1')
        ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
        ->where('t1.deleted')->eq(0)
        ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
        ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
        ->beginIF(!$isAdmin)->andWhere('t1.id')->in($productIDList)->fi()
        ->andWhere('t1.vision')->eq($this->config->vision)->fi()
        ->beginIF($status == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
        ->beginIF(!in_array($status, array('all', 'noclosed', 'involved', 'review'), true))->andWhere('t1.status')->in($status)->fi()
        ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
        ->fetchAll('id');

    return $products;
}
