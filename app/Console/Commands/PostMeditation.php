<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Meditation;
class PostMeditation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To post the medication on sechedule';

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
        //
        $meditationObj = new Meditation;
        $meditationObj->where('schedule','<=',date('Y-m-d H:i:s') )
            ->where('status','approved')
            ->update(['posted' => 1]);
    }
}
