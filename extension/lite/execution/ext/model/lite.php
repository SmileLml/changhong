<?php
/**
 * 设置导航。
 * Set navigation.
 *
 * @param  int    $executionID
 * @param  int    $buildID
 * @param  string $extra
 * @access public
 * @return void
 */
public function setMenu($executionID, $buildID = 0, $extra = '')
{
    common::setMenuVars('execution', $executionID);

    $execution = $this->getById($executionID);
    if(isset($this->lang->execution->menu->kanban))
    {
        $this->loadModel('project')->setMenu($execution->project);
        $this->lang->kanbanProject->menu->execution['subMenu'] = new stdClass();
        if($this->app->rawModule == 'tree') unset($this->lang->kanbanProject->menu->execution['subMenu']);
    }

    $kanbanList    = $this->getList($execution->project, 'kanban', 'all');
    $currentKanban = zget($kanbanList, $execution->id, '');
    if(empty($currentKanban)) $this->accessDenied();

    $subMenu = $this->lang->execution->menu;

    foreach($subMenu as $key => $value)
    {
        if(common::hasPriv('execution', $key))
        {
            $tmpValue = explode('|', $value['link']);
            $subMenu->{$key}['name']   = $tmpValue[0];
            $subMenu->{$key}['module'] = $tmpValue[1];
            $subMenu->{$key}['method'] = $tmpValue[2];
            $subMenu->{$key}['vars']   = $tmpValue[3];
        }
        else
        {
            unset($subMenu->$key);
        }
    }
    $this->setSubNav($kanbanList, $currentKanban);
}

/**
 * 设置三级导航。
 * Set sub navigation.
 *
 * @param  array  $kanbanList
 * @param  object $currentKanban
 * @access public
 * @return void
 */
public function setSubNav($kanbanList, $currentKanban)
{
    $lowerModule = strtolower($this->app->rawModule);
    $lowerMethod = strtolower($this->app->rawMethod);

    $modulePageNav  = "";
    $modulePageNav .= "<div class='btn-group angle-btn active'><div class='btn-group'>";
    $modulePageNav .= "<button data-toggle='dropdown' type='button' class='btn' style='border-radius: 4px;'>{$currentKanban->name} <span class='caret'></span></button>";
    $modulePageNav .= "<ul class='dropdown-menu'>";
    foreach($kanbanList as $kanbanID => $kanban)
    {
        if($this->session->kanbanview && strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$this->session->kanbanview}|") !== false)
        {
            $method = $this->session->kanbanview;
        }
        elseif($this->cookie->kanbanview && strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$this->cookie->kanbanview}|") !== false)
        {
            $method = $this->cookie->kanbanview;
        }
        else
        {
            $method = 'kanban';
        }

        $module = 'execution';

        if($lowerModule == 'task' and $lowerMethod == 'create')
        {
            $module = 'task';
            $method = 'create';
        }

        $modulePageNav .=  '<li>' . html::a(helper::createLink($module, $method, "execution=$kanban->id"), $kanban->name) . '</li>';
    }
    $modulePageNav .= "</ul></div></div>";

    if($lowerModule == 'execution' and strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") !== false)
    {
        $this->session->set('kanbanview', $lowerMethod);
        setcookie('kanbanview', $lowerMethod, $this->config->cookieLife, $this->config->webRoot, '', false, true);
    }

    if(strpos('|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") !== false) $this->lang->TRActions = $this->getTRActions($lowerMethod);
    if(strpos('|relation|maintainrelation|', "|{$lowerMethod}|") !== false) $this->lang->TRActions = $this->getTRActions('gantt');
    if($lowerModule == 'task' or ($lowerModule == 'execution' and strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") === false))
    {
        if($this->session->kanbanview)
        {
            $this->lang->TRActions = $this->getTRActions($this->session->kanbanview);
        }
        elseif($this->cookie->kanbanview)
        {
            $this->lang->TRActions = $this->getTRActions($this->cookie->kanbanview);
        }
    }

    $this->lang->modulePageNav = $modulePageNav;
}

/**
 * 设置右上角页面切换按钮。
 * Set right top page switch button.
 *
 * @param string $currentMethod
 * @access public
 * @return void
 */
public function getTRActions($currentMethod)
{
    $subMenu = $this->lang->execution->menu;

    foreach($subMenu as $key => $value)
    {
        if(common::hasPriv('execution', $key))
        {
            $tmpValue = explode('|', $value['link']);
            $subMenu->{$key}['name']   = $tmpValue[0];
            $subMenu->{$key}['module'] = $tmpValue[1];
            $subMenu->{$key}['method'] = $tmpValue[2];
            $subMenu->{$key}['vars']   = $tmpValue[3];
        }
        else
        {
            unset($subMenu->$key);
        }
    }

    $TRActions  = '';
    $TRActions .= "<div class='btn-group dropdown'>";
    $TRActions .= html::a("javascript:;", "<i class='icon icon-" . $this->lang->execution->icons[$currentMethod]."'> </i>" . $subMenu->{$currentMethod}['name'] . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
    $TRActions .= "<ul class='dropdown-menu pull-right'>";
    foreach($subMenu as $subKey => $subName)
    {
        $active = $this->session->kanbanview == $subKey ? "class='active'" : '';
        $TRActions .=  "<li $active>" . html::a(helper::createLink('execution', $subName['method'], $subName['vars']), "<i class='icon icon-" . $this->lang->execution->icons[$subName['method']] . "'></i> " . $subName['name']) . '</li>';
    }

    $TRActions .= "</ul></div>";
    return $TRActions;
}
