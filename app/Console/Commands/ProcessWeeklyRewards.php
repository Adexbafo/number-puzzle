<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Leaderboard;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessWeeklyRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-reward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reward the top 100 professional users and reset the leaderboard for the week.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Weekly Reward Processing...');

        $weekId = Carbon::now()->format('Y-\WW');

        // 1. Identify Top 100 Professional Users
        $topProUsers = Leaderboard::with('user.profile')
            ->whereHas('user.profile', function ($q) {
                $q->where('level', 'professional');
            })
            ->orderByDesc('score')
            ->take(100)
            ->get();

        if ($topProUsers->isEmpty()) {
            $this->warn('No professional users found on the leaderboard.');
        } else {
            $this->info('Found ' . $topProUsers->count() . ' professional users for rewards.');

            DB::transaction(function () use ($topProUsers, $weekId) {
                foreach ($topProUsers as $index => $entry) {
                    // Record reward in history
                    DB::table('weekly_rewards')->insert([
                        'user_id' => $entry->user_id,
                        'score' => $entry->score,
                        'rank' => $index + 1,
                        'week_identifier' => $weekId,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $this->info('Top 100 users recorded in weekly_rewards table.');
        }

        // 2. Reset Logic Hardening
        $this->info('Resetting game state for all users...');

        DB::transaction(function () {
            // Reset Lifelines and Scores in profiles table
            Profile::query()->update([
                'lifelines' => 5,
                'score' => 0,
                'round' => 1
            ]);

            // Clear the Leaderboard table
            Leaderboard::truncate();
        });

        $this->info('Leaderboard reset and lifelines restored to 5.');
        $this->info('Weekly reward cycle complete! 🚀');

        return Command::SUCCESS;
    }
}
