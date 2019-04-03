<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TaskReminder:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Task Reminder when a particular task is almost due.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Check user's reminder time.
        $users = User::all();

        foreach ($users as $user) {
            $settings = Setting::where('reminder_time', Carbon::now()->format('H:i'))->get();
            $currDate = Carbon::today();

            if ($settings->isEmpty()) {
                dd("No one");
            }

            foreach ($settings as $setting) {
                $tasks = Task::where('user_id', $user->id)
                ->where('status', 0)
                ->whereDate('start_date', '<=', $currDate)
                ->whereDate('due_date', '>=', $currDate)
                ->get();

                $remindTasks = collect(); 

                //Get the task is cloased to duedate
                foreach ($tasks as $task) {
                    if ($currDate->diffInDays($task->due_date) <= $setting->day_before_remind) {
                        $remindTasks->push($task);
                    }   
                }

                if (!empty($remindTasks)) {
                    //Send email to remind the user
                    try {
                        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                        $beautymail->send('emails.reminder', ['user' => $user, 'tasks' => $remindTasks], function($message) use ($user)
                        {
                            $message
                            ->from('admin@pmxglobal.com.my', 'Breathe')
                            ->to($user->email, $user->name)
                            ->subject('Breathe Reminder');
                        });
                    }
                    catch (\Exception $e)
                    {
                      
                    }
                }       
            }
        }
    }
}
