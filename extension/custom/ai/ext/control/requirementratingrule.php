<?php
helper::importControl('ai');
class myAI extends ai
{
    public function requirementRatingRule()
    {
        $this->ratingRules('requirement');
    }
}
