<?php
helper::importControl('ai');
class myAI extends ai
{
    public function storyRatingRule()
    {
        $this->ratingRules('story');
    }
}
