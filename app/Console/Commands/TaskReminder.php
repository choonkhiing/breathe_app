<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use \App\User;
use \App\Task;
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

        $remindTasks = collect(); 

        foreach ($users as $user) {
            $currDate = Carbon::today();

            $tasks = Task::with("group")->where('user_id', $user->id)
            ->where('status', 0)
            ->where('group_id', 0)
            ->whereDate('start_date', '<=', $currDate)
            ->whereDate('due_date', '>=', $currDate)
            ->orderBy('group_id', 'ASC')
            ->get();

            foreach ($tasks as $task) {
                $settings = Setting::where('reminder_time', Carbon::now()->format('h:i A'))
                ->where('task_id', $task->id)->get();

                foreach ($settings as $setting) {
                    //Get the task is closed to duedate
                    if ($currDate->diffInDays($task->due_date) <= $setting->day_before_remind) {
                        $remindTasks->push($task);
                    }   
                }               
            }

            $groupTasks = Task::leftJoin("groups", "tasks.group_id", "groups.id")
            ->leftJoin("group_members", "group_members.group_id", "groups.id")
            ->where('group_members.user_id', $user->id)
            ->where('tasks.group_id', ">", 0)
            ->where('tasks.status', 0)
            ->where('groups.status', 1)
            ->where('group_members.status', 1)
            ->whereDate('start_date', '<=', $currDate)
            ->whereDate('due_date', '>=', $currDate)
            ->orderBy('tasks.group_id', 'ASC')
            ->select("tasks.id as task_id", "groups.*", "group_members.*")
            ->get();

            foreach ($groupTasks as $groupTask) {
                $settings = Setting::where('reminder_time', '<=', Carbon::now()->format('h:i A'))
                ->where('task_id', $groupTask->task_id)->get();

                foreach ($settings as $setting) {
                    //Get the task is closed to duedate
                    if ($currDate->diffInDays($groupTask->due_date) <= $setting->day_before_remind) {
                        $remindTasks->push($groupTask);
                    }   
                }               
            }
        }

        $remindTasks = $remindTasks->groupBy("user_id");
        
        if (!$remindTasks->isEmpty()) {
            //Send email to remind the user
            try {
                foreach ($remindTasks as $key => $remindTask) {
                    $user = User::find($key);

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
