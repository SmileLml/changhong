<style>
#logDetails xmp{white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;}
</style>
<div>
  <pre id="logDetails">
   <?php
     $result = json_decode($params->params);
     if(empty($result)) $result = $params->params;
     a($result);
   ?>
 </pre>
</div>
