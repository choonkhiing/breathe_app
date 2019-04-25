<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use \App\Setting;
use \App\Group;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TaskReminder:everyMinute';

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
            $settings = Setting::where('reminder_time', '<=', Carbon::now()->format('h:i A'))->get();
            $currDate = Carbon::today();
            $remindTasks = collect(); 

            foreach ($settings as $setting) {
                $tasks = Task::with("group")->where('user_id', $user->id)
                ->where('status', 0)
                ->where('id', $setting->task_id)
                ->whereDate('start_date', '<=', $currDate)
                ->whereDate('due_date', '>=', $currDate)
                ->get();

                //Get the task is closed to duedate
                foreach ($tasks as $task) {
                    if ($currDate->diffInDays($task->due_date) <= $setting->day_before_remind) {
                        $remindTasks->push($task);
                    }   
                }
            }
        }

        $remindTasks = $remindTasks->groupBy("user_id");

        if (!$remindTasks->isEmpty()) {
                    //Send email to remind the user
            try {
                foreach ($remindTasks as $remindTask) {
                    $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                    $beautymail->send('emails.reminder', ['user' => $user, 'tasks' => $remindTask], function($message) use ($user)
                    {
                        $message
                        ->from('ckooi@geekycs.com', 'Breathe')
                        ->to($user->email, $user->name)
                        ->subject('Breathe Reminder');
                    });
                }
            }
            catch (\Exception $e)
            {
                dd($e->getMessage());
            }
        }       
    }
}
