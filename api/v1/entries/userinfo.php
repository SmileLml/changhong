<?php
/**
 * The bug info entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 **/
class userinfoEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function get()
    {
        $this->loadModel('user');
        $userID  = $this->param('user', 0);
        $account = $this->param('account', '');
        if($userID)
        {
            $user = $this->user->getById($userID, 'id');
        }
        elseif($account)
        {
            $user = $this->user->getById($account);
        }
        else
        {
            return $this->sendError(400, 'Need user or account param.');
        }

        unset($user->password);
        $user = $this->filterFields($user, 'id,account,realname');

        $isAdmin = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $rights  = $this->user->authorize($user->account);
        $view    = $this->user->grantUserView($user->account, $rights['acls']);

        $info = new stdclass();
        $info->profile = $user;

        $fields = $this->param('fields', 'product');
        $fields = explode(',', strtolower($fields));
        foreach($fields as $field)
        {
            switch($field)
            {
            case 'product':
                $info->product = array('total' => 0, 'products' => array());

                $products = $this->loadModel('product')->getListByAccount(0, 'all', $isAdmin, explode(',', $view->products), $user->account);
                if($products)
                {
                    foreach($products as $productID => $product) $products[$productID] = $this->format($product, 'createdBy:user,createdDate:time,deleted:bool');
                    $info->product['total']    = count($products);
                    $info->product['products'] = $products;
                }
                break;
            case 'project':
                $info->project = array('total' => 0, 'projects' => array());

                $projects = $this->loadModel('project')->getListByAccount(0, 'all', $isAdmin, explode(',', $view->projects), $user->account);
                if($projects)
                {
                    foreach($projects as $projectID => $project) $projects[$projectID] = $this->format($project, 'realBegan:date,realEnd:date,openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,suspendedDate:date,deleted:bool');
                    $info->project['total']    = count($projects);
                    $info->project['projects'] = $projects;
                }
                break;
            }
        }

        return $this->send(200, $info);
    }
}
