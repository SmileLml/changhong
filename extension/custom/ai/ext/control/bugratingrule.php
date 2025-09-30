<?php
helper::importControl('ai');
class myAI extends ai
{
    public function bugRatingRule()
    {
        $this->ratingRules('bug');
    }
}
