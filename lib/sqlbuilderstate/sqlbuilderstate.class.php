<?php
class sqlBuilderState
{
    /**
     * from
     *
     * @var array
     * @access public
     */
    public $from = array();

    /**
     * joins
     *
     * @var array
     * @access public
     */
    public $joins = array();

    /**
     * funcs
     *
     * @var array
     * @access public
     */
    public $funcs = array();

    /**
     * wheres
     *
     * @var array
     * @access public
     */
    public $wheres = array();

    /**
     * querys
     *
     * @var array
     * @access public
     */
    public $querys = array();

    /**
     * groups
     *
     * @var array
     * @access public
     */
    public $groups = false;

    /**
     * tableDesc
     *
     * @var array
     * @access public
     */
    public $tableDesc = array();

    /**
     * queryFilterSelectOptions
     *
     * @var array
     * @access public
     */
    public $queryFilterSelectOptions = array();

    /**
     * sql
     *
     * @var string
     * @access public
     */
    public $sql = '';

    /**
     * step
     *
     * @var string
     * @access public
     */
    public $step = 'table';

    /**
     * error
     *
     * @var array
     * @access public
     */
    public $error = array();

    /**
     * __construct method.
     *
     * @param  pivot      object
     * @param  drills     array
     * @param  clientLang string
     * @access public
     * @return void
     */
    public function __construct($from = null, $joins = null, $funcs = null, $wheres = null, $querys = null, $groups = null)
    {
        if(!is_array($joins))
        {
            if(empty($from)) $from = array(
                'from'      => array(),
                'joins'     => array(),
                'funcs'     => array(),
                'wheres'    => array(),
                'querys'    => array(),
                'groups'    => false,
                'step'      => 'table',
                'tableDesc' => array(),
                'queryFilterSelectOptions' => array()
            );
            extract($from);
        }

        $this->from      = $from;
        $this->joins     = $joins;
        $this->funcs     = $funcs;
        $this->wheres    = $wheres;
        $this->querys    = $querys;
        $this->groups    = $groups;
        $this->step      = $step ?? 'table';
        $this->tableDesc = $tableDesc ?? array();

        $this->queryFilterSelectOptions = $queryFilterSelectOptions ?? array();

        if(empty($from) || !isset($from['table'])) $this->setFrom();
        $this->processAddJoins();
        $this->processAddFuncs();
        $this->processAddWheres();
        $this->processAddQuerys();
        $this->processSelect();
        if($this->groups === true) $this->enableGroupBy();
    }

    /**
     * Add table desc.
     *
     * @param  string $table
     * @param  array  $list
     * @access public
     * @return void
     */
    public function addTableDesc($table, $list)
    {
        if(!isset($this->tableDesc[$table])) $this->tableDesc[$table] = $list;
    }

    /**
     * Set from.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function setFrom($table = '')
    {
        $this->from = array('table' => $table, 'alias' => 't1', 'select' => array());
    }

    /**
     * Add join
     *
     * @param  string|array $left
     * @param  string       $alias
     * @param  string       $columnA
     * @param  string       $fieldA
     * @param  string       $fieldB
     * @access public
     * @return void
     */
    public function addJoin($left, $alias = '', $columnA = '', $fieldA = '', $fieldB = '')
    {
        if(is_array($left))
        {
            $this->joins[] = $left;
            return;
        }
        $join = array();
        $join['table']  = $left;
        $join['alias']  = $alias;
        $join['select'] = array();
        $join['on']     = array($columnA, $fieldA, '=', $alias, $fieldB);

        $this->joins[] = $join;
    }

    /**
     * Add func.
     *
     * @param  string|array $type
     * @param  string       $table
     * @param  string       $field
     * @param  string       $function
     * @param  string       $alias
     * @access public
     * @return void
     */
    public function addFunc($type, $table = '', $field = '', $function = '', $alias = '', $name = '')
    {
        if(is_array($type))
        {
            $this->funcs[] = $type;
            return;
        }

        $func = array();
        $func['type']     = $type;
        $func['table']    = $table;
        $func['field']    = $field;
        $func['function'] = $function;
        $func['alias']    = $alias;
        $func['name']     = $name;

        $this->funcs[] = $func;
    }

    /**
     * Add where group.
     *
     * @param  array $group
     * @access public
     * @return void
     */
    public function addWhereGroup($group)
    {
        $this->wheres[] = $group;
    }

    /**
     * Add where item.
     *
     * @param  int          $index
     * @param  string|array $table
     * @param  string       $field
     * @param  string       $operator
     * @param  string       $value
     * @access public
     * @return void
     */
    public function addWhereItem($index, $table = '', $field = '', $operator = '=', $value = '', $conditionOperator = 'and')
    {
        $item = is_array($table) ? $table : array($table, $field, $operator, null, $value, $conditionOperator);
        $this->wheres[$index]['items'][] = $item;
    }

    /**
     * add query filter.
     *
     * @param  string $table
     * @param  string $field
     * @param  string $name
     * @param  string $type
     * @param  string $typeOption
     * @param  string $default
     * @access public
     * @return void
     */
    public function addQueryFilter($table = '', $field = '', $name = '', $type = 'select', $typeOption = 'user', $default = '')
    {
        if(is_array($table))
        {
            $this->querys[] = $table;
            return;
        }

        $query = array();
        $query['table']      = $table;
        $query['field']      = $field;
        $query['name']       = $name;
        $query['type']       = $type;
        $query['typeOption'] = $typeOption;
        $query['default']    = $default;

        $this->querys[] = $query;
    }

    /**
     * set agg func.
     *
     * @access public
     * @return void
     */
    public function setAggFunc()
    {
        $this->funcs = array_filter($this->funcs, function ($func) {
            return $func['type'] !== 'agg';
        });
        if($this->groups === false) return;

        foreach($this->groups as $group)
        {
            if($group['type'] == 'group') continue;

            list($table, $field, $alias, $function, $name) = $group['select'];
            $this->addFunc('agg', $table, $field, $function, "{$alias}_{$function}", $name);
        }
    }

    /**
     * addGroupBy
     *
     * @param  int    $type
     * @param  int    $order
     * @param  int    $select
     * @param  string $function
     * @access public
     * @return void
     */
    public function addGroupBy($type, $order, $select, $function = 'count')
    {
        list($table, $field, $selectFunc) = array($select[0], $select[1], $select[3]);

        $fieldList = $this->getTableDescList($table);
        $name = $table . '_' . $fieldList[$field];
        if(!empty($selectFunc)) $name = $name . '_' . $selectFunc;

        $select[3] = $function;
        $select[4] = $name;
        $this->groups[]  = array('select' => $select, 'type' => $type, 'order' => $order, 'name' => $name);
    }

    /**
     * Set group by.
     *
     * @access public
     * @return void
     */
    public function enableGroupBy()
    {
        $selects = array_merge($this->getSelects(), $this->getFuncSelects());

        $this->groups = array();
        foreach($selects as $index => $select) $this->addGroupBy('agg', $index, $select);

        $this->setAggFunc();
    }

    /**
     * Get select tables.
     *
     * @param  array $tableList
     * @access public
     * @return array
     */
    public function getSelectTables($tableList)
    {
        $selectTables = array();
        $tables       = $this->joins;
        array_unshift($tables, $this->from);

        foreach($tables as $join)
        {
            $table = $join['table'];
            if(empty($table)) continue;
            $alias = $join['alias'];
            $name  = $tableList[$table];
            $selectTables[$alias] = "$name($alias)";
        }

        return $selectTables;
    }

    /**
     * Get func selects.
     *
     * @access public
     * @return array
     */
    public function getFuncSelects()
    {
        $funcs   = $this->getFuncs('func', true);
        $selects = array();

        foreach($funcs as $func)
        {
            list($table, $field, $alias, $function) = array($func['table'], $func['field'], $func['alias'], $func['function']);
            $selects[] = array($table, $field, $alias, $function);
        }

        return $selects;
    }

    /**
     * Get selects.
     *
     * @access public
     * @return array
     */
    public function getSelects()
    {
        $selects = array();
        $tables  = $this->joins;
        array_unshift($tables, $this->from);

        foreach($tables as $table)
        {
            $alias = $table['alias'];
            foreach($table['select'] as $field) $selects[] = array($alias, $field, "{$alias}_{$field}", null);
        }

        return $selects;
    }

    /**
     * Get selects with key.
     *
     * @access public
     * @return array
     */
    public function getSelectsWithKey($selects)
    {
        $selectsWithKey = array();
        foreach($selects as $select)
        {
            list($table, $field, $alias) = $select;
            $key = "{$table}_{$field}_{$alias}";
            $selectsWithKey[$key] = $select;
        }

        return $selectsWithKey;
    }

    /**
     * Get funcs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getFuncs($type, $skipEmpty = false)
    {
        $funcs = array();
        foreach($this->funcs as $index => $func)
        {
            $hasEmpty = empty($func['table']) || empty($func['field']);
            $hasEmpty = $hasEmpty || empty($func['function']) || empty($func['alias']);
            if($skipEmpty && $hasEmpty) continue;

            if($func['type'] == $type || $type == 'all') $funcs[$index] = $func;
        }
        return $funcs;
    }

    /**
     * Get group by.
     *
     * @param  bool   $sort
     * @param  bool   $onlyField
     * @access public
     * @return array
     */
    public function getGroupBy($sort = true, $onlyField = true)
    {
        if($this->groups === false) return array();

        $groupBys = array_filter($this->groups, function ($group) {
            return $group['type'] === 'group';
        });

        if($sort) uasort($groupBys, function($a, $b) {return $a['order'] <= $b['order'] ? -1 : 1;});

        if($onlyField)
        {
            foreach($groupBys as $index => $groupBy)
            {
                $select = $groupBy['select'];
                list($table, $field) = array($select[0], $select[1]);
                $groupBys[$index] = array($table, $field);
            }
        }

        return array_values($groupBys);
    }

    /**
     * Get next table alias.
     *
     * @access public
     * @return string
     */
    public function getNextTableAlias()
    {
        $indexes = array();
        foreach($this->joins as $join)
        {
            if(!is_array($join)) continue;

            $alias = $join['alias'];
            $indexes[] = (int)str_replace('t', '', $alias);
        }
        $max = empty($indexes) ? 1 : max($indexes);
        $next = $max + 1;

        return "t{$next}";
    }

    /**
     * Get table desc field list.
     *
     * @param  string $alias
     * @access public
     * @return array
     */
    public function getTableDescList($alias)
    {
        $tableDesc = $this->tableDesc;
        $tables    = $this->joins;
        array_unshift($tables, $this->from);

        foreach($tables as $join)
        {
            if($alias == $join['alias'] && isset($tableDesc[$join['table']])) return $tableDesc[$join['table']];
        }

        return array();
    }

    /**
     * Get json
     *
     * @access public
     * @return string
     */
    public function getJson()
    {
        $data = array();
        $data['from']   = $this->from;
        $data['joins']  = $this->joins;
        $data['funcs']  = $this->funcs;
        $data['wheres'] = $this->wheres;
        $data['querys'] = $this->querys;
        $data['groups'] = $this->groups;

        return json_encode($data);
    }

    /**
     * buildSelects
     *
     * @access public
     * @return void
     */
    public function buildSelects()
    {
        $selects = array();
        if($this->groups !== false)
        {
            foreach($this->groups as $group)
            {
                list($table, $field, $alias, $function) = $group['select'];
                $selects[] = $group['type'] == 'agg' ? array($table, $field, $alias, $function) : array($table, $field, $alias);
            }

            return $selects;
        }

        return array_merge($this->getSelects(), $this->getFuncSelects('func'));
    }

    /**
     * Build functions.
     *
     * @access public
     * @return void
     */
    public function buildFunctions()
    {
        $functions = $this->getFuncs('all', true);
        $funcArr = array();
        foreach($functions as $function) $funcArr[] = array($function['table'], $function['field'], $function['alias'], $function['function']);

        return $funcArr;
    }

    /**
     * build wheres.
     *
     * @access public
     * @return array
     */
    public function buildWheres()
    {
        $wheres     = array();
        $groupCount = count($this->wheres);
        foreach($this->wheres as $groupIndex => $group)
        {
            $groups = array();
            foreach($group['items'] as $itemIndex => $item)
            {
                list($columnA, $fieldA, $operator, $columnB, $fieldB, $conditionOperator) = $item;
                if($itemIndex !== 0) $groups[] = $conditionOperator;
                $groups[] = array($columnA, $fieldA, $operator, $columnB, $fieldB);
            }

            $wheres[] = $groups;
            if($groupIndex + 1 < $groupCount) $wheres[] = $group['operator'];
        }

        return $wheres;
    }

    /**
     * Build querys.
     *
     * @access public
     * @return array
     */
    public function buildQuerys()
    {
        $querys = array();
        $hasWhere = count($this->wheres) > 0;
        foreach($this->querys as $index => $query)
        {
            if($index != 0 || $hasWhere) $querys[] = 'and';
            $variable = "var$index";
            $table    = $query['table'];
            $field    = $query['field'];
            $type     = $query['type'];

            if($type != 'multipleselect')
            {
                $querys[] = array(null, "if(\${$variable}='',1=2,\${$variable}=`{$table}`.`{$field}`)");
            }
            else
            {
                $querys[] = array(null, "if(\"\${$variable}\"='',1=2,`{$table}`.`{$field}` in (\${$variable}))");
            }
        }

        return $querys;
    }

    /**
     * check from.
     *
     * @access public
     * @return bool|string
     */
    public function checkFrom()
    {
        if(empty($this->from['table'])) return $this->getError('from', 'table', $this->from['alias']);

        return true;
    }

    /**
     * check joins
     *
     * @access public
     * @return bool|string
     */
    public function checkJoins()
    {
        foreach($this->joins as $join)
        {
            $alias = $join['alias'];
            if(empty($join['table'])) return $this->getError('join', 'table', $alias);

            list($columnA, $fieldA, $fieldB) = array($join['on'][0], $join['on'][1], $join['on'][4]);
            if(empty($columnA)) return $this->getError('join', 'columnA', $alias);
            if(empty($fieldA))  return $this->getError('join', 'fieldA', $alias);
            if(empty($fieldB))  return $this->getError('join', 'fieldB', $alias);
        }

        return true;
    }

    /**
     * check selects.
     *
     * @access public
     * @return bool|string
     */
    public function checkSelects()
    {
        $from    = $this->from;
        $joins   = $this->joins;
        $select  = array();
        $select  = array_merge($select, $from['select']);
        foreach($joins as $join) $select = array_merge($select, $join['select']);

        $checkFuncs = $this->checkFuncs();
        if(empty($select) && $checkFuncs === false) return $this->getError('select', 'field');

        return true;
    }

    /**
     * Check funcs.
     *
     * @access public
     * @return bool|string
     */
    public function checkFuncs($type = 'func')
    {
        $funcs = $this->getFuncs($type);
        if(empty($funcs)) return false;

        $checkDuplicate = array();
        foreach($funcs as $index => $func)
        {
            if(empty($func['table']))    return $this->getError('func', 'table', $index);
            if(empty($func['field']))    return $this->getError('func', 'field', $index);
            if(empty($func['function'])) return $this->getError('func', 'function', $index);
            if(empty($func['alias']) && !is_numeric($func['alias'])) return $this->getError('func', 'alias', $index);

            $alias = $func['alias'];
            if(!isset($checkDuplicate[$alias]))
            {
                $checkDuplicate[$alias] = $index;
            }
            else
            {
                return $this->getError('func', 'duplicate', $index);
            }
        }

        return true;
    }

    /**
     * Check wheres.
     *
     * @access public
     * @return bool|string
     */
    public function checkWheres()
    {
        foreach($this->wheres as $groupIndex => $group)
        {
            foreach($group['items'] as $itemIndex => $item)
            {
                list($table, $field, $value) = array($item[0], $item[1], $item[4]);
                if(empty($table)) return $this->getError('where', "{$groupIndex}_{$itemIndex}_0");
                if(empty($field)) return $this->getError('where', "{$groupIndex}_{$itemIndex}_1");
                if(empty($value) && !is_numeric($value)) return $this->getError('where', "{$groupIndex}_{$itemIndex}_4");
            }
        }

        return true;
    }

    /**
     * Check querys.
     *
     * @access public
     * @return bool|string
     */
    public function checkQuerys()
    {
        $querys = $this->querys;
        foreach($querys as $index => $query)
        {
            if(empty($query['table'])) return $this->getError('query', 'table', $index);
            if(empty($query['field'])) return $this->getError('query', 'field', $index);
            if(empty($query['name']) && !is_numeric($query['name']))  return $this->getError('query', 'name', $index);
        }

        return true;
    }

    /**
     * Set error.
     *
     * @param  string $key
     * @access public
     * @return void
     */
    public function setError($key)
    {
        $this->error[$key] = true;
    }

    /**
     * Get Error.
     *
     * @param  string $key
     * @param  string $type
     * @param  string $field
     * @access public
     * @return string
     */
    public function getError($key, $type = '', $field = '')
    {
        return implode('_', array_filter(array($key, $type, $field), function($value) { return $value !== ''; }));
    }

    /**
     * Has error.
     *
     * @param  string $key
     * @param  string $type
     * @param  string $field
     * @access public
     * @return bool
     */
    public function hasError($key, $type = '', $field = '')
    {
        $key = $this->getError($key, $type, $field);
        return isset($this->error[$key]);
    }


    /**
     * Process check all.
     *
     * @access public
     * @return void
     */
    public function processCheckAll()
    {
        $from  = $this->from;
        $joins = $this->joins;
        if($from['select'] == '*') $this->from['select'] = array_keys($this->getTableDescList($from['alias']));

        foreach($joins as $index => $join)
        {
            if($join['select'] == '*') $this->joins[$index]['select'] = array_keys($this->getTableDescList($join['alias']));
        }
    }

    /**
     * Process funcs.
     *
     * @access public
     * @return void
     */
    public function processFuncs()
    {
        foreach($this->funcs as $index => $func)
        {
            $alias = $func['alias'];
            $table = $func['table'];
            $field = $func['field'];
            if(!empty($alias) || empty($table) || empty($field)) continue;

            $this->funcs[$index]['alias'] = "{$table}_{$field}_{$index}";
        }
    }

    /**
     * Process group by.
     *
     * @access public
     * @return void
     */
    public function processGroupBy()
    {
        if($this->groups === false) return;
        list($selects, $groups) = $this->updateGroupsFromSelects();

        $this->groups = $this->reorderGroupBy($groups);

        $order = count($this->groups);
        foreach($selects as $select)
        {
            $this->addGroupBy('agg', $order, $select);
            $order += 1;
        }
        $this->setAggFunc();
    }

    /**
     * Process query filters.
     *
     * @access public
     * @return void
     */
    public function processQueryFilters()
    {
        foreach($this->querys as $index => $query)
        {
            $table = $query['table'];
            $field = $query['field'];
            $name  = $query['name'];
            if(!empty($field) && empty($name))
            {
                $fieldList = $this->getTableDescList($table);
                $this->querys[$index]['name'] = zget($fieldList, $field, $field);
            }
        }
    }

    /**
     * Process select.
     *
     * @access public
     * @return void
     */
    public function processSelect()
    {
        $fromSelect = $this->from['select'];
        if($fromSelect == '*') $this->from['select'] = array_keys($this->getTableDescList($this->from['alias']));

        foreach($this->joins as $index => $join)
        {
            if($join['select'] !== '*') continue;
            $this->joins[$index]['select'] = array_keys($this->getTableDescList($join['alias']));
        }
    }

    /**
     * Process add joins.
     *
     * @access public
     * @return void
     */
    public function processAddJoins()
    {
        $joins = $this->joins;
        $alias = $this->getNextTableAlias();
        $this->joins = array();
        foreach($joins as $join)
        {
            if($join == 'add')
            {
                $this->addJoin('', $alias);
                continue;
            }
            $this->addJoin($join);
        }
    }

    /**
     * Process add funcs.
     *
     * @access public
     * @return void
     */
    public function processAddFuncs()
    {
        $funcs = $this->funcs;
        $this->funcs = array();
        foreach($funcs as $func)
        {
            if($func == 'add')
            {
                $this->addFunc('func');
                continue;
            }

            $this->addFunc($func);
        }
    }

    /**
     * Process add wheres.
     *
     * @access public
     * @return void
     */
    public function processAddWheres()
    {
        $wheres = $this->wheres;
        $this->wheres = array();
        foreach($wheres as $index => $group)
        {
            if($group == 'add') $group = array('items' => array(), 'operator' => 'and');

            $this->addWhereGroup(array('items' => array(), 'operator' => $group['operator']));
            $items = $group['items'];
            if(empty($items)) $items[] = 'add';
            foreach($items as $itemIndex => $item)
            {
                if($item == 'add') $this->addWhereItem($index);
                else               $this->addWhereItem($index, $item);
            }
        }
    }

    /**
     * Process add querys
     *
     * @access public
     * @return void
     */
    public function processAddQuerys()
    {
        $querys = $this->querys;
        $this->querys = array();
        foreach($querys as $query)
        {
            if($query == 'add') $query = '';
            $this->addQueryFilter($query);
        }
    }

    /**
     * Update group from selects.
     *
     * @access public
     * @return array
     */
    public function updateGroupsFromSelects()
    {
        $selects = array_merge($this->getSelects(), $this->getFuncSelects());
        $selects = $this->getSelectsWithKey($selects);

        if($this->groups === false) return array(array(), array());

        $groups = array();
        foreach($this->groups as $group)
        {
            list($table, $field, $alias) = $group['select'];
            $key = "{$table}_{$field}_{$alias}";

            if(!isset($selects[$key])) continue;
            $groups[] = $group;
            unset($selects[$key]);
        }

        return array($selects, $groups);
    }

    /**
     * Reorder group by.
     *
     * @param  array  $groups
     * @access public
     * @return array
     */
    public function reorderGroupBy($groups)
    {
        uasort($groups, function($a, $b) {return $a['order'] <= $b['order'] ? -1 : 1;});

        $order = 0;
        foreach($groups as $index => $group)
        {
            $groups[$index]['order'] = $order;
            $order += 1;
        }
        ksort($groups, SORT_NUMERIC);

        return $groups;
    }

    /**
     * build
     *
     * @param  array $selects
     * @param  array $from
     * @param  array $joins
     * @param  array $functions
     * @param  array $wheres
     * @param  array $querys
     * @param  array $groups
     * @access public
     * @return object
     */
    public function build($parser)
    {
        $checkList = array('checkFrom', 'checkJoins', 'checkSelects', 'checkFuncs', 'checkWheres', 'checkQuerys');
        foreach($checkList as $check) if(is_string($this->$check())) return;

        $parser->createStatement();

        /* from */
        $from = array($this->from['table'], null, $this->from['alias']);
        $parser->setFrom($parser->getExpression($from));

        /* join */
        foreach($this->joins as $join)
        {
            $alias  = $join['alias'];
            $table  = $join['table'];
            $on     = $join['on'];

            $onExprs      = $parser->getCondition($on);
            $leftJoinExpr = $parser->getLeftJoin($table, $alias, $onExprs);
            $parser->addJoin($leftJoinExpr);
        }

        /* select */
        $selects = $this->buildSelects();
        foreach($selects as $select) $parser->addSelect($parser->getExpression($select));

        /* where */
        $wheres = $this->buildWheres();
        if(!empty($wheres)) $parser->addWhere($parser->combineConditions($parser->getConditionsFromArray($wheres)));

        /* querys */
        $querys = $this->buildQuerys();
        if(!empty($querys)) $parser->addWhere($parser->combineConditions($parser->getConditionsFromArray($querys)));

        /* group by */
        $groups = $this->getGroupBy(true, true);
        if(!empty($groups)) foreach($groups as $group) $parser->addGroup($parser->getGroup($parser->getExpression($group)));

        $this->sql = $parser->statement->build();
    }
}
