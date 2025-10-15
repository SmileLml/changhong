<?php
class dingtalkModel extends model
{
    /**
     * 获取钉钉配置
     * Get dingtalk config.
     *
     * @access public
     * @return object
     */
    public function getConfig()
    {
        $this->loadModel('setting');

        $dingtalkConfigs = new stdclass();

        foreach(explode(',', $this->lang->dingtalk->fields) as $field) $dingtalkConfigs->$field = isset($this->config->dingtalk->$field) ? $this->config->dingtalk->$field : '';

        return $dingtalkConfigs;
    }
    
    /**
     * 保存钉钉配置
     * Save dingtalk config.
     *
     * @access public
     * @return bool
     */
    public function saveConfig()
    {
        $dingtalkConfigs = fixer::input('post')->get();

        $this->checkEmptyConfig($dingtalkConfigs);
        if(dao::isError()) return false;

        $this->loadModel('setting');
        foreach($dingtalkConfigs as $key => $value)
        {
            $this->setting->setItem('system.common.dingtalk.' . $key, $value);

            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * 检查钉钉配置是否为空
     * Check dingtalk config is empty.
     *
     * @access public
     * @param  array  $dingtalkConfigs
     * @return bool
     */
    public function checkEmptyConfig($dingtalkConfigs)
    {
        foreach($dingtalkConfigs as $key => $value)
        {
            if(empty($value)) dao::$errors[$key] = sprintf($this->lang->error->notempty, $this->lang->dingtalk->$key);
        }

        return true;
    }

    /**
     * 获取对象信息
     * Get object info
     * 
     * @param  int    $objectID
     * @param  string $objectType
     * @return object
     */
    public function getObjectInfo($objectID, $objectType)
    {
        $fieldType = $objectType . 'WordFields';
        $fields    = $this->config->dingtalk->$fieldType;
        $table     = constant('TABLE_' . strtoupper($objectType));
        $object    = $this->dao->select($fields)->from($table)->where('id')->eq($objectID)->fetch();

        $this->loadModel('file');
        if($objectType == 'task') $object = $this->file->replaceImgURL($object, 'desc');
        if($objectType == 'bug')  $object = $this->file->replaceImgURL($object, 'steps');
        
        $files = $this->file->getByObject($objectType, $objectID);

        foreach($files as $file) $file->url = common::getSysURL() . helper::createLink('file', 'download', "fileID={$file->id}");

        $object->files = $files;

        return $object;
    }

    /**
     * 获取实施（解决）过程记录
     * Get process info
     * 
     * @param  int    $objectID
     * @param  string $objectType
     * @return array
     */
    public function getProcessInfo($objectID, $objectType)
    {
        return $this->dao->select('comment')
            ->from(TABLE_ACTION)
            ->where('objectID')->eq($objectID)
            ->andWhere('objectType')->eq($objectType)
            ->andWhere('comment')->ne('')
            ->orderBy('id_asc')
            ->fetchAll();
    }

    /**
     * 创建任务Word文档
     * Create task Word document
     * 
     * @param int $taskId
     * @return bool
     */
    public function createTaskWord($taskID = 1)
    {
        $task = $this->getObjectInfo($taskID, 'task');
        if(!$task) return false;
        
        $task->projectName    = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($task->project)->fetch('name');
        $task->executionName  = $this->dao->select('name')->from(TABLE_EXECUTION)->where('id')->eq($task->execution)->fetch('name');
        $task->moduleName     = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($task->module)->fetch('name');
        $task->parentTaskName = $this->dao->select('name')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch('name');
        $task->taskType       = zget($this->lang->task->typeList, $task->type, $task->type);
        $task->pri            = zget($this->lang->task->priList, $task->pri, $task->pri);
        $task->status         = zget($this->lang->task->statusList, $task->status, $task->status);
        
        $this->generateWordDocument($task, 'task');
        
        return true;
    }

    /**
     * 创建bug的Word文档
     * Create bug Word document
     * 
     * @param int $taskId
     * @return bool
     */
    public function createBugWord($bugID = 1)
    {
        $bug = $this->getObjectInfo($bugID, 'bug');
        if(!$bug) return false;
        
        $bug->projectName   = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($bug->project)->fetch('name');
        $bug->executionName = $this->dao->select('name')->from(TABLE_EXECUTION)->where('id')->eq($bug->execution)->fetch('name');
        $bug->moduleName    = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($bug->module)->fetch('name');
        $bug->productName   = $this->dao->select('name')->from(TABLE_PRODUCT)->where('id')->eq($bug->product)->fetch('name');
        $bug->bugType       = zget($this->lang->bug->typeList, $bug->type, $bug->type);
        $bug->pri           = zget($this->lang->bug->priList, $bug->pri, $bug->pri);
        $bug->severity      = zget($this->lang->bug->severityList, $bug->severity, $bug->severity);
        $bug->status        = zget($this->lang->bug->statusList, $bug->status, $bug->status);
        $bug->resolution    = zget($this->lang->bug->resolutionList, $bug->resolution, $bug->resolution);
        $bug->resolvedBuild = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->eq($bug->resolvedBuild)->fetch('name');
        
        $this->generateWordDocument($bug, 'bug');
        
        return true;
    }
    
    /**
     * 生成Word文档
     * Generate Word document
     * 
     * @param  object $task
     * @param  string $type
     * @return void
     */
    private function generateWordDocument($object, $type)
    {
        $headers = array();
        foreach(explode(',', $this->config->dingtalk->{$type . 'WordBasicFields'}) as $field) $headers[] = $this->lang->dingtalk->$field;

        $funcName    = 'get' . $type . 'Rows';
        $rows        = $this->$funcName($object);
        $fileName    = $type == 'task' ? $object->name : $object->title;
        $path        = $this->app->getTmpRoot() . 'dingtalkword/' . $type . '/' . $object->id . '_' . $fileName . '.docx';
        $wordContent = $this->buildWordContent($headers, $rows, $object, $type);
        
        $dir = dirname($path);
        if(!is_dir($dir)) mkdir($dir, 0755, true);
        
        $this->createWordDocument($wordContent, $path);
        exit;
    }
    
    /**
     * 构建Word文档内容
     * Build Word document content
     * 
     * @param  array  $headers
     * @param  array  $rows
     * @param  object $task
     * @return array
     */
    private function buildWordContent($headers, $rows, $object, $type)
    {
        $funcName = 'get' . $type . 'Sections';
        $sections = $this->$funcName($object, $headers, $rows);
        $title    = $type == 'task' ? $object->name : $object->title;
        $wordData = array('title' => $title, 'sections' => $sections);

        return $wordData;
    }

    /**
     * 获取任务部分
     * Get task sections
     * 
     * @param  object $task
     * @param  array  $headers
     * @param  array  $rows
     * @return array
     */
    public function getTaskSections($task, $headers, $rows)
    {
        $sections[] = array('title' => $this->lang->dingtalk->taskAttributeInfo, 'type' => 'table', 'data' => array('headers' => $headers, 'rows' => array($rows)));
        $sections[] = array('title' => $this->lang->dingtalk->taskDescriptionInfo, 'type' => 'richtext', 'content' => $task->desc);
        
        $processes = $this->getProcessInfo($task->id, 'task');
        if(!empty($processes)) 
        {
            $processContent = '';
            foreach($processes as $process) $processContent .= $process->comment . "\n";
            $sections[] = array('title' => $this->lang->dingtalk->taskProcessInfo, 'type' => 'richtext', 'content' => $processContent);
        }

        if($task->files) $sections[] = $this->getFileSections($task->files);

        return $sections;
    }

    /**
     * 获取Bug部分
     * Get bug sections
     * 
     * @param  object $bug
     * @param  array  $headers
     * @param  array  $rows
     * @return array
     */
    public function getBugSections($bug, $headers, $rows)
    {
        $sections[] = array('title' => $this->lang->dingtalk->bugAttributeInfo, 'type' => 'table', 'data' => array('headers' => $headers, 'rows' => array($rows)));
        $sections[] = array('title' => $this->lang->dingtalk->bugStepsInfo, 'type' => 'richtext', 'content' => $bug->steps);
        
        $processes = $this->getProcessInfo($bug->id, 'bug');
        if(!empty($processes)) 
        {
            $processContent = '';
            foreach($processes as $process) $processContent .= $process->comment . "\n";
            $sections[] = array('title' => $this->lang->dingtalk->bugHistoryInfo, 'type' => 'richtext', 'content' => $processContent);
        }

        if($bug->files) $sections[] = $this->getFileSections($bug->files);

        return $sections;
    }

    /**
     * 获取Bug行数据
     * Get bug rows data
     * 
     * @param  object $bug
     * @return array
     */
    public function getBugRows($bug)
    {
        $rows = array();
        $rows[] = $bug->id;
        $rows[] = $bug->projectName ? $bug->projectName : $this->lang->dingtalk->notHave;
        $rows[] = $bug->executionName ? $bug->executionName : $this->lang->dingtalk->notHave;
        $rows[] = $bug->moduleName ? $bug->moduleName : $this->lang->dingtalk->notHave;
        $rows[] = $bug->productName ? $bug->productName : $this->lang->dingtalk->notHave;
        $rows[] = $bug->bugType;
        $rows[] = $bug->pri;
        $rows[] = $bug->severity;
        $rows[] = $bug->status;
        $rows[] = $bug->resolution;
        $rows[] = $bug->resolvedBuild ? $bug->resolvedBuild : $this->lang->dingtalk->notHave;

        return $rows;
    }

    /**
     * 获取附件信息表格
     * Get attachment info table
     * 
     * @param  array $files
     * @return array
     */
    private function getFileSections($files)
    {
        $attachmentHeaders = array($this->lang->dingtalk->attachmentName, $this->lang->dingtalk->attachmentLink);

        $attachmentRows = array();
        foreach($files as $file) $attachmentRows[] = array($file->title, $file->url);
        
        // 为附件表格设置更合理的列宽：名称列较窄，链接列更宽
        $data = array();
        $data['headers']   = $attachmentHeaders;
        $data['rows']      = $attachmentRows;
        $data['colWidths'] = array(2500, 9000);

        return array('title' => $this->lang->dingtalk->attachmentInfo, 'type'  => 'table', 'data'  => $data);
    }

    /**
     * 获取任务行数据
     * Get task rows data
     * 
     * @param  object $task
     * @return array
     */
    public function getTaskRows($task)
    {
        $rows = array();
        $rows[] = $task->id;
        $rows[] = $task->projectName ? $task->projectName : $this->lang->dingtalk->notHave;
        $rows[] = $task->executionName ? $task->executionName : $this->lang->dingtalk->notHave;
        $rows[] = $task->moduleName ? $task->moduleName : $this->lang->dingtalk->notHave;
        $rows[] = $task->parentTaskName ? $task->parentTaskName : $this->lang->dingtalk->notHave;
        $rows[] = $task->taskType;
        $rows[] = $task->pri;
        $rows[] = $task->status;

        return $rows;
    }

    /**
     * 添加富文本内容到Word文档
     * Add rich text content to Word document
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $content
     * @return void
     */
    private function addRichTextContent($section, $content)
    {
        if(empty($content)) return;
        
        $processedContent = $this->preprocessHTML($content);
        
        $this->pauseHtmlTag($section, $processedContent);
    }
    
    /**
     * 预处理HTML内容
     * Preprocess HTML content
     * 
     * @param  string $content
     * @return string
     */
    private function preprocessHTML($content)
    {
        // 确保内容不为空
        if(empty($content)) return '';
        
        // 标准化换行符
        $content = str_replace(array("\r\n", "\r"), "\n", $content);
        // 处理段落标签
        $content = preg_replace('/([^>\s]\s*)(<p|<ul|<ol)/i', "\\1<br />\\2", $content);
        // 清理多余空白字符
        $content = preg_replace('/[ \t]+/', ' ', $content);
        // 处理特殊字符编码
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        // 移除可能导致问题的控制字符
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);
        
        return $content;
    }
    
    /**
     * 解析HTML标签并转换为Word格式
     * Parse HTML tags and convert to Word format
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return void
     */
    private function pauseHtmlTag($section, $text)
    {
        if(empty($text)) return;
        
        // 按优先级处理各种HTML标签
        if($this->processTableTags($section, $text)) return;
        if($this->processListTags($section, $text)) return;
        if($this->processBreakTags($section, $text)) return;
        if($this->processImageTags($section, $text)) return;
        if($this->processLinkTags($section, $text)) return;
        if($this->processSpanTags($section, $text)) return;
        
        // 处理其他HTML标签
        $this->processOtherHtmlTags($section, $text);
    }
    
    /**
     * 处理表格标签
     * Process table tags
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool
     */
    private function processTableTags($section, $text)
    {
        if(!preg_match('/<table[^>]*>.*?<\/table>/is', $text, $tableMatches)) return false;
        
        $parts = preg_split('/(<table[^>]*>.*?<\/table>)/is', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        foreach($parts as $part) 
        {
            if(empty($part)) continue;
            
            if(preg_match('/<table[^>]*>.*?<\/table>/is', $part))  $this->processTableInHtml($section, $part);
            if(!preg_match('/<table[^>]*>.*?<\/table>/is', $part)) $this->pauseHtmlTag($section, $part);
        }

        return true;
    }
    
    /**
     * 处理列表标签
     * Process list tags (ul, ol)
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool 是否处理了列表标签
     */
    private function processListTags($section, $text)
    {
        if(!preg_match('/<(ul|ol)[^>]*>.*?<\/(ul|ol)>/is', $text, $listMatches)) return false;
        
        $parts = preg_split('/(<(ul|ol)[^>]*>.*?<\/(ul|ol)>)/is', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        foreach($parts as $part) 
        {
            if(empty($part)) continue;
            
            if(preg_match('/<(ul|ol)[^>]*>.*?<\/(ul|ol)>/is', $part))  $this->processListInHtml($section, $part);
            if(!preg_match('/<(ul|ol)[^>]*>.*?<\/(ul|ol)>/is', $part)) $this->pauseHtmlTag($section, $part);
        }
        
        return true;
    }
    
    /**
     * 处理换行和段落标签
     * Process break and paragraph tags
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool 是否处理了换行标签
     */
    private function processBreakTags($section, $text)
    {
        $out = array();
        preg_match_all('/(<br ?(\/?)>|<\/p>|<\/li>)/U', $text, $out);
        $splitByBR = preg_split("/<br ?(\/?)>|<\/p>|<\/li>/U", $text);
        
        if(empty($out[0])) return false;
        
        foreach($splitByBR as $i => $content)
        {
            if($content) $this->pauseHtmlTag($section, $content);

            if(!isset($out[1][$i])) continue;
            // 添加段落分隔
            $section->addTextBreak();
        }
        
        return true;
    }
    
    /**
     * 处理图片标签
     * Process image tags
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool 是否处理了图片标签
     */
    private function processImageTags($section, $text)
    {
        $out = array();
        preg_match_all("/<img[^>]+src\s*=\s*([\"\'])([^\"\']*)\\1[^>]*>/i", $text, $out);
        $splitByIMG = preg_split("/<img[^>]+src\s*=\s*([\"\'])([^\"\']*)\\1[^>]*>/i", $text);
        
        if(empty($out[0])) return false;
        
        foreach($splitByIMG as $i => $content)
        {
            if($content) $this->pauseHtmlTag($section, $content);

            if(!isset($out[0][$i])) continue;
            $this->addImageToWord($section, $out[0][$i]); // 传递完整的img标签
        }

        return true;
    }
    
    /**
     * 处理链接标签
     * Process link tags
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool 是否处理了链接标签
     */
    private function processLinkTags($section, $text)
    {
        $out = array();
        preg_match_all("/<a .*href=([\"|\'])(.*)\\1.*>(.*)<\/a>/U", $text, $out);
        $splitByA = preg_split("/<a .*href=([\"|\'])(.*)\\1.*>(.*)<\/a>/U", $text);
        
        if(empty($out[0])) return false;
        
        foreach($splitByA as $i => $content)
        {
            if($content) $this->pauseHtmlTag($section, $content);

            if(!isset($out[3][$i])) continue;
            $content = trim($out[3][$i]);
            if(empty($content)) continue;
            $this->addLinkToWord($section, $out[3][$i], $out[2][$i]);
        }

        return true;
    }
    
    /**
     * 处理span标签（样式文本）
     * Process span tags with styles
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $text
     * @return bool 是否处理了span标签
     */
    private function processSpanTags($section, $text)
    {
        $out = array();

        preg_match_all("/<span[^>]*style\s*=\s*[\"']([^\"']*)[\"'][^>]*>(.*?)<\/span>/is", $text, $out);
        $noTags = preg_split("/<span[^>]*style\s*=\s*[\"']([^\"']*)[\"'][^>]*>(.*?)<\/span>/is", $text);
        
        if(empty($out[1]) || empty($out[2])) return false;
        
        $processedStyles = $this->parseSpanStyles($out[1]);

        foreach($noTags as $i => $content)
        {
            if($content) $this->pauseHtmlTag($section, $content);

            if(!isset($out[2][$i])) continue;
            $content = trim($out[2][$i]);
            if(empty($content)) continue;
            
            $styles = isset($processedStyles[$i]) ? $processedStyles[$i] : array();
            $this->addStyledTextToWord($section, $content, $styles);
        }
        
        return true;
    }
    
    /**
     * 解析span标签的样式
     * Parse styles from span tags
     * 
     * @param  array $styleStrings
     * @return array
     */
    private function parseSpanStyles($styleStrings)
    {
        $processedStyles = array();
        
        foreach($styleStrings as $i => $styles)
        {
            $styles = trim($styles);
            if(empty($styles)) continue;
            
            $styleArray = array();
            $stylePairs = explode(';', $styles);
            foreach($stylePairs as $style)
            {
                $style = trim($style);
                if(empty($style)) continue;
                if(strpos($style, ':') === false) continue;
                
                $parts = explode(':', $style, 2);
                if(count($parts) === 2) 
                {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    $styleArray[$key] = $value;
                }
            }
            $processedStyles[$i] = $styleArray;
        }
        
        return $processedStyles;
    }
    
    /**
     * 添加图片到Word文档
     * Add image to Word document
     * 
     * @param  \PhpOffice\PhpWord\Element\Section $section
     * @param  string $imgTag
     * @return void
     */
    private function addImageToWord($section, $imgTag)
    {
        if(empty($imgTag)) return;
        
        $src       = $this->extractImageSrc($imgTag);
        $width     = $this->extractImageDimension($imgTag, 'width');
        $height    = $this->extractImageDimension($imgTag, 'height');
        $alignment = $this->extractImageAlignment($imgTag);
        
        if(empty($src)) return;
        
        if(preg_match('/^http[s]?:\/\//', $src)) return $section->addText($src);

        $imagePath = $this->processImage($src);
        
        if($imagePath && file_exists($imagePath)) 
        {
            $imageInfo      = getimagesize($imagePath);
            $originalWidth  = $imageInfo[0];
            $originalHeight = $imageInfo[1];
            
            if($width <= 0 || $height <= 0) 
            {
                $width = $originalWidth;
                $height = $originalHeight;
            }
            
            $maxWidth = $this->config->dingtalk->maxImageWidth;
            if($width > $maxWidth) 
            {
                $ratio  = $maxWidth / $width;
                $width  = $maxWidth;
                $height = intval($height * $ratio);
            }
            
            $imageOptions = array('width' => $width, 'height' => $height);
            
            if($alignment !== null) $imageOptions['alignment'] = $alignment;
            
            $section->addImage($imagePath, $imageOptions);
        } 
        else 
        {
            $section->addText($this->lang->dingtalk->imageNotFound . ": " . $src, array('size' => 10, 'italic' => true, 'color' => 'FF0000'));
        }
    }
    
    /**
     * 从img标签中提取src属性
     * Extract src attribute from img tag
     * 
     * @param  string $imgTag
     * @return string
     */
    private function extractImageSrc($imgTag)
    {
        if(preg_match('/src\s*=\s*(["\'])([^"\']*)\\1/i', $imgTag, $matches)) return $matches[2];
        
        return '';
    }
    
    /**
     * 从img标签中提取width或height属性
     * Extract width or height attribute from img tag
     * 
     * @param  string $imgTag
     * @param  string $type
     * @return int
     */
    private function extractImageDimension($imgTag, $type)
    {
        if(preg_match('/' . $type . '\s*=\s*(["\']?)(\d+)\\1/i', $imgTag, $matches)) return intval($matches[2]);
        
        return 0;
    }
    
    /**
     * 从img标签中提取对齐属性
     * Extract alignment attribute from img tag
     * 
     * @param  string $imgTag
     * @return string
     */
    private function extractImageAlignment($imgTag)
    {
        if(preg_match('/style\s*=\s*["\']([^"\']*)["\']/i', $imgTag, $styleMatches)) 
        {
            $style = $styleMatches[1];
            if(preg_match('/text-align\s*:\s*(left|center|right|justify)/i', $style, $alignMatches)) return $this->convertAlignmentToWord($alignMatches[1], 'text');
        }
        
        // 检查align属性
        if(preg_match('/align\s*=\s*(["\']?)(left|center|right|justify)\\1/i', $imgTag, $matches)) return $this->convertAlignmentToWord($matches[2], 'text');
        
        return null; // 没有设置对齐方式
    }
    
    /**
     * 将HTML对齐方式转换为Word对齐方式
     * Convert HTML alignment to Word alignment
     * 
     * @param  string $htmlAlignment
     * @param  string $type
     * @return string
     */
    private function convertAlignmentToWord($htmlAlignment, $type = 'text')
    {
        $jc = $type === 'table' ? \PhpOffice\PhpWord\SimpleType\JcTable : \PhpOffice\PhpWord\SimpleType\Jc;

        switch(strtolower($htmlAlignment)) 
        {
            case 'left':
                return $jc::START;
            case 'center':
                return $jc::CENTER;
            case 'right':
                return $jc::END;
            case 'justify':
                return $jc::BOTH;
            default:
                return null;
        }
    }
    
    /**
     * 从表格标签中提取对齐属性
     * Extract alignment attribute from table tag
     * 
     * @param  string $tableTag
     * @return string
     */
    private function extractTableAlignment($tableTag)
    {
        // 检查style属性中的text-align
        if(preg_match('/style\s*=\s*["\']([^"\']*)["\']/i', $tableTag, $styleMatches)) 
        {
            $style = $styleMatches[1];
            if(preg_match('/text-align\s*:\s*(left|center|right|justify)/i', $style, $alignMatches)) return $this->convertAlignmentToWord($alignMatches[1], 'table');
            
        }
        
        // 检查align属性
        if(preg_match('/align\s*=\s*(["\']?)(left|center|right|justify)\\1/i', $tableTag, $matches)) return $this->convertAlignmentToWord($matches[2], 'table');
        
        return null; // 没有设置对齐方式
    }
    
    
    /**
     * 添加链接到Word文档
     * Add link to Word document
     */
    private function addLinkToWord($section, $text, $href)
    {
        if(empty($text) || empty($href)) return;
        
        // 清理文本和链接
        $text = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $text);
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $href = preg_replace('/&(quot|#34);/i', '&', $href);
        $href = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $href);
        
        // 在PhpWord中添加链接
        $section->addLink($href, $text, array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));
    }
    
    /**
     * 添加带样式的文本到Word文档
     * Add styled text to Word document
     */
    private function addStyledTextToWord($section, $text, $styles)
    {
        if(empty($text)) return;
        
        try
        {
            // 清理文本 - 更安全的处理方式
            $text = trim($text);
            $text = strip_tags($text);
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/u', '', $text);
            $text = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML5);
            $text = str_replace(array('&nbsp;', '&amp;', '&lt;', '&gt;', '&quot;'), array(' ', '&', '<', '>', '"'), $text);
            
            if(empty($text)) return;
            
            // 转换样式
            $wordStyles = $this->transformStyleToWord($styles);
            
            // 确保有默认样式
            if(empty($wordStyles)) $wordStyles = array('size' => 12);
            
            $section->addText($text, $wordStyles);
        } 
        catch(Exception $e) 
        {
            // 如果样式处理失败，添加普通文本
            $text = trim(strip_tags($text));
            if(!empty($text)) $section->addText($text, array('size' => 12));
        }
    }
    
    /**
     * 处理其他HTML标签
     * Process other HTML tags
     */
    private function processOtherHtmlTags($section, $text)
    {
        // 处理粗体和斜体
        $text = preg_replace('/<(strong|b)>(.*?)<\/(strong|b)>/i', '**$2**', $text);
        $text = preg_replace('/<(em|i)>(.*?)<\/(em|i)>/i', '*$2*', $text);
        
        // 处理普通文本
        $text = trim(strip_tags($text));
        $text = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $text);
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $text = str_replace(array('&nbsp;'), array(' '), $text);
        
        if(!empty($text)) $section->addText($text, array('size' => 12));
    }
    
    /**
     * 处理HTML中的表格
     * Process table in HTML
     */
    private function processTableInHtml($section, $text)
    {
        preg_match('/<table[^>]*>(.*?)<\/table>/is', $text, $tableMatch);
        if(empty($tableMatch[1])) return;
        
        preg_match('/<table([^>]*)>/is', $text, $tableTagMatch);
        $tableAlignment = $this->extractTableAlignment($tableTagMatch[1] ?? '');
        
        $tableOptions = $this->config->dingtalk->tableOptions;
        
        // 只有设置了对齐方式才添加alignment属性
        if($tableAlignment !== null) $tableOptions['alignment'] = $tableAlignment;
        
        $table = $section->addTable($tableOptions);
        
        // 提取行 - 改进正则表达式
        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $tableMatch[1], $rowMatches);
        
        foreach($rowMatches[1] as $rowIndex => $rowHtml) 
        {
            $table->addRow();
            
            // 提取单元格 - 改进正则表达式
            preg_match_all('/<(td|th)[^>]*>(.*?)<\/(td|th)>/is', $rowHtml, $cellMatches);
            
            foreach($cellMatches[2] as $cellIndex => $cellContent) 
            {
                $cellObj  = $table->addCell(1500);
                $cellText = trim(strip_tags($cellContent));
                $cellText = htmlspecialchars_decode($cellText, ENT_QUOTES);
                $cellText = str_replace(array('&nbsp;'), array(' '), $cellText);
                
                if(!empty($cellText)) 
                {
                    $isHeader = isset($cellMatches[1][$cellIndex]) && strtolower($cellMatches[1][$cellIndex]) === 'th';
                    $cellObj->addText($cellText, array('size' => 10, 'bold' => $isHeader));
                } else 
                {
                    $cellObj->addText(' ', array('size' => 10));
                }
            }
        }
    }
    
    /**
     * 处理HTML中的列表
     * Process list in HTML
     */
    private function processListInHtml($section, $text)
    {
        // 处理无序列表
        if(preg_match('/<ul[^>]*>(.*?)<\/ul>/is', $text, $ulMatch)) 
        {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $ulMatch[1], $liMatches);
            foreach($liMatches[1] as $item) 
            {
                $itemText = trim(strip_tags($item));
                if(!empty($itemText)) $section->addText("• " . $itemText, array('size' => 12), array('spaceAfter' => 60));
            }
        }
        
        // 处理有序列表
        if(preg_match('/<ol[^>]*>(.*?)<\/ol>/is', $text, $olMatch)) 
        {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $olMatch[1], $liMatches);
            $counter = 1;
            foreach($liMatches[1] as $item) 
            {
                $itemText = trim(strip_tags($item));
                if(!empty($itemText)) 
                {
                    $section->addText($counter . ". " . $itemText, array('size' => 12), array('spaceAfter' => 60));
                    $counter++;
                }
            }
        }
    }
    
    /**
     * 转换CSS样式到Word样式
     * Transform CSS styles to Word styles
     */
    private function transformStyleToWord($styles)
    {
        $wordStyles = array();
        
        if(empty($styles) || !is_array($styles)) return $wordStyles;
        
        foreach($styles as $key => $value) 
        {
            $key   = trim(strtolower($key));
            $value = trim($value);
            
            if(empty($value)) continue;
            
            switch($key) 
            {
                case 'font-weight':
                    if($value == 'bold' || $value == 'bolder' || intval($value) >= 700) $wordStyles['bold'] = true;
                    break;
                case 'font-style':
                    if($value == 'italic' || $value == 'oblique') $wordStyles['italic'] = true;
                    break;
                case 'text-decoration':
                    if(strpos($value, 'underline') !== false)    $wordStyles['underline'] = \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE;
                    if(strpos($value, 'line-through') !== false) $wordStyles['strikethrough'] = true;
                    break;
                case 'color':
                    $color = $this->normalizeColor($value);
                    if($color) $wordStyles['color'] = $color;
                    break;
                case 'font-size':
                    $size = $this->normalizeFontSize($value);
                    if($size > 0) $wordStyles['size'] = $size;
                    break;
                case 'font-family':
                    // Word 支持字体族，但需要确保字体存在
                    $fontFamily = $this->normalizeFontFamily($value);
                    if($fontFamily) $wordStyles['name'] = $fontFamily;
                    break;
                case 'background-color':
                    $bgColor = $this->normalizeColor($value);
                    if($bgColor) $wordStyles['bgColor'] = $bgColor;
                    break;
            }
        }
        
        return $wordStyles;
    }
    
    /**
     * 标准化颜色值
     * Normalize color value
     */
    private function normalizeColor($color)
    {
        $color = trim($color);
        if(empty($color)) return null;
        
        // 处理 #rgb 格式
        if($color[0] == '#') 
        {
            $color = substr($color, 1);
            // 如果是3位十六进制，转换为6位
            if(strlen($color) == 3) $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
            return strtoupper($color);
        }
        
        // 处理 rgb() 格式
        if(preg_match('/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/', $color, $matches)) 
        {
            $r = str_pad(dechex($matches[1]), 2, '0', STR_PAD_LEFT);
            $g = str_pad(dechex($matches[2]), 2, '0', STR_PAD_LEFT);
            $b = str_pad(dechex($matches[3]), 2, '0', STR_PAD_LEFT);
            return strtoupper($r . $g . $b);
        }
        
        return null;
    }
    
    /**
     * 标准化字体大小
     * Normalize font size
     */
    private function normalizeFontSize($size)
    {
        $size = trim($size);
        if(empty($size)) return 0;
        
        // 处理 px 单位
        if(preg_match('/(\d+(?:\.\d+)?)px/', $size, $matches)) return intval($matches[1]);
        
        // 处理 pt 单位
        if(preg_match('/(\d+(?:\.\d+)?)pt/', $size, $matches)) return intval($matches[1]);
        
        // 处理纯数字
        if(preg_match('/(\d+(?:\.\d+)?)/', $size, $matches)) return intval($matches[1]);
        
        return 0;
    }
    
    /**
     * 标准化字体族
     * Normalize font family
     */
    private function normalizeFontFamily($fontFamily)
    {
        $fontFamily = trim($fontFamily);
        if(empty($fontFamily)) return null;
        
        // 移除引号
        $fontFamily = trim($fontFamily, '"\'');
        
        // 处理多个字体，取第一个
        $fonts   = explode(',', $fontFamily);
        $font    = trim($fonts[0]);
        $fontMap = $this->config->dingtalk->fontMap;
        
        return isset($fontMap[$font]) ? $fontMap[$font] : $font;
    }
    
    
    /**
     * 处理图片路径
     * Process image path
     */
    public function processImage($path)
    {                                                                                        
        // 处理动态路径格式 /file-read-4.png 
        if(preg_match('/-(\d+)\./', $path, $matches))                      
        {                                                                               
            $this->loadModel('file');                                                   
            $file = $this->file->getByID($matches[1]);                                  
            if(!$file) return null;                                                    

            return $this->file->saveAsTempFile($file);
        }                                                                               

        // 处理动态路径格式 {9.png} {9.jpg}等格式"
        if(preg_match('/\{(\d+)\.\w+}/', $path, $matches))
        {
            $this->loadModel('file');
            $file = $this->file->getByID($matches[1]);
            if(!$file) return null;

            return $this->file->saveAsTempFile($file);
        }

        // 处理静态路径                                                                 
        $basePath     = rtrim($this->config->word->filePath, '/') . '/';                
        $realFilePath = $basePath . ltrim($path, '/');                                                                                                                  

        // 验证路径有效性                                                               
        if(!file_exists($realFilePath)) return null;                                                                

        return realpath($realFilePath); // 返回绝对路径                                 
    }

    /**
     * 创建Word文档
     * Create Word document using PhpWord
     */
    private function createWordDocument($wordData, $path)
    {
        $this->app->loadClass('phpword', true);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // 设置文档属性
        $phpWord->getDocInfo()->setCreator($this->lang->zentaoPMS)->setLastModifiedBy($this->lang->zentaoPMS)->setTitle($wordData['title'])->setDescription($this->lang->dingtalk->taskDetailInfo);
        
        // 添加标题样式 - 主标题更大更粗
        $phpWord->addTitleStyle(1, array('size' => 20, 'bold' => true), array('spaceAfter' => 300, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        $phpWord->addTitleStyle(2, array('size' => 14, 'bold' => true), array('spaceAfter' => 120));
        
        // 添加段落样式
        $phpWord->addParagraphStyle('normal', array('spaceAfter' => 120));
        $phpWord->addParagraphStyle('description', array('size' => 10, 'spaceAfter' => 200));
        
        // 创建新节
        $section = $phpWord->addSection();
        
        // 添加主标题
        $section->addTitle($wordData['title'], 1);
        
        // 添加说明文字
        if(isset($wordData['description'])) $section->addText($wordData['description'], array('size' => 10), array('spaceAfter' => 200));
        
        // 处理各个部分
        foreach($wordData['sections'] as $sectionData) 
        {
            $section->addTitle($sectionData['title'], 2);
            
            if($sectionData['type'] == 'table') 
            {
                // 创建表格，设置边框样式
                $table = $section->addTable($this->config->dingtalk->tableOptions);

                // 计算列宽（优先使用传入的 colWidths），单位：twips
                $headers   = isset($sectionData['data']['headers']) ? $sectionData['data']['headers'] : array();
                $colCount  = count($headers);
                $colWidths = array();
                if(isset($sectionData['data']['colWidths']) && is_array($sectionData['data']['colWidths']))
                {
                    $colWidths = $sectionData['data']['colWidths'];
                }
                else
                {
                    // 默认列宽：适当加大单元格宽度
                    for($i = 0; $i < $colCount; $i++) $colWidths[$i] = 3000;
                }

                // 添加表头
                $table->addRow();
                foreach($headers as $i => $header)
                {
                    $width = isset($colWidths[$i]) ? $colWidths[$i] : end($colWidths);
                    $cell  = $table->addCell($width);
                    $cell->addText($header, array('bold' => true, 'size' => 10));
                }

                // 添加数据行
                foreach($sectionData['data']['rows'] as $row) 
                {
                    $table->addRow();
                    foreach($row as $i => $cell)
                    {
                        $width   = isset($colWidths[$i]) ? $colWidths[$i] : end($colWidths);
                        $cellObj = $table->addCell($width);
                        $cellObj->addText($cell, array('size' => 10));
                    }
                }
            } 

            if($sectionData['type'] == 'richtext') $this->addRichTextContent($section, $sectionData['content']);
        }
        
        // 保存文档
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);
    }
}