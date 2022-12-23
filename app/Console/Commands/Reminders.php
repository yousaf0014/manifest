<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserReminder;
class Reminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to users on sechedule';

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
        $userReminderObj = new UserReminder;
        $reminders = $userReminderObj->where('time','<=',date('Y-m-d H:i:s'))->where('active',1)->get();
        foreach($reminders as $rm){
            $user = $rm->user()->first();
            $rm->active = 0;
            $rm->save();
        }
    }
}
