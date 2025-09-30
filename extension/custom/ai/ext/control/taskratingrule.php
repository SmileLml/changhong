<?php
helper::importControl('ai');
class myAI extends ai
{
    public function taskRatingRule()
    {
        $this->ratingRules('task');
    }
}
