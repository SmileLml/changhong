<?php

public function loadAIWeightField()
{
    $this->loadModel('ai');
    if(!isset($this->config->ai->triggerAction[$this->app->rawModule][$this->app->rawMethod])) return false;
    $weightFields = $this->ai->getWeightFields($this->app->rawModule, $this->app->rawMethod);
    if(empty($weightFields)) return false;
    $rules = $this->ai->getRulesByObjectType($this->app->rawModule);
}