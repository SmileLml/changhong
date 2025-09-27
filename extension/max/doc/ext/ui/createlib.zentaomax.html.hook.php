<?php
/**
 * The createlib view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$project   = data('project');
$execution = data('execution');
if(data('type') == 'project'   && !empty($project->isTpl))   query("#object")->prop('disabled', 'disabled');
if(data('type') == 'execution' && !empty($execution->isTpl)) query("#object")->prop('disabled', 'disabled');
