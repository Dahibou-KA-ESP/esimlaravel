<?php

namespace App\Console;
use App\Models\Talons;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('email:send-insurance-reminders')->dailyAt('08:00');
        $schedule->call(function () {
            
            $sevenDaysFromNow = Carbon::now()->addDays(7)->format('Y-m-d');
            $policies = \App\Models\Talons::whereRaw('STR_TO_DATE(date_echeance, "%d/%m/%Y") <= ?', [$sevenDaysFromNow])->get();

            Log::info('Policies retrieved: ' . $policies->count());

            foreach ($policies as $policy) {
                $user = $policy->client; // Ensure the relationship is correctly defined
                Log::info('Notifying user: ' . $user->email); // Log user email or ID

                $user->notify(new \App\Notifications\InsuranceRenewalReminder($policy));
            }

            Log::info('Scheduled task completed.');
        })->cron('45 16 * * *');
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
