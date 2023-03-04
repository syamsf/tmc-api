<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\PingJob;

class PingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dummy Ping Job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      PingJob::dispatch()->onQueue('ping-job');
    }
}
