<?php

class RuleService
{
    /**
     * Handle promotion logic
     */
    public function checkPromotion($profile)
    {
        if ($profile->level === 'amateur') {
            if ($profile->round >= 10 && $profile->lifelines == 5) {
                $profile->level = 'professional';
                $this->resetProgress($profile);
            }
        }
    }

    /**
     * Handle relegation logic
     */
    public function checkRelegation($profile)
    {
        if ($profile->level === 'professional') {

            $safe = (
                ($profile->round >= 30 && $profile->lifelines >= 2)
            );

            if (!$safe) {
                $profile->level = 'amateur';
                $this->resetProgress($profile);
            }
        }
    }

    /**
     * Reset round progression
     */
    private function resetProgress($profile)
    {
        $profile->round = 1;
        $profile->lifelines = 5;
    }
}