<?php

namespace App\Console\Commands;

use App\Repositories\OvertimeRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OvertimePendingCron extends Command
{

    private $overtime;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:overtime-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update overtime status to cancel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OvertimeRepository $overtimeRepository)
    {
        $this->overtime = $overtimeRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info($this->overtime->autoOvertimeCanceling());
    }
}
