<?php

namespace App\Console;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            try {
                $schedule->command($task->command)
                    ->when(
                        function () use ($task) {
                            if (Carbon::now()->setSecond(0)->greaterThanOrEqualTo($task->date_time)) {
                               $task->delete();
                               return true;
                            }

                            return false;
                        }
                    );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
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
