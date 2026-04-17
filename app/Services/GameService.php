<?php

namespace App\Services;

class GameService
{
    public function generateQuestion()
    {
        $ops = ['+', '-', '*', '/'];
        $op = $ops[array_rand($ops)];

        $start = rand(2, 10);
        $step = rand(1, 5);

        $numbers = [$start];

        for ($i = 1; $i < 4; $i++) {
            $prev = $numbers[$i - 1];

            switch ($op) {
                case '+': $numbers[] = $prev + $step; break;
                case '-': $numbers[] = $prev - $step; break;
                case '*': $numbers[] = $prev * $step; break;
                case '/': $numbers[] = intval($prev / ($step ?: 1)); break;
            }
        }

        $missing = rand(0, 3);
        $answer = $numbers[$missing];
        $numbers[$missing] = '_';

        return [
            'pattern' => implode(',', $numbers),
            'answer' => $answer
        ];
    }
}