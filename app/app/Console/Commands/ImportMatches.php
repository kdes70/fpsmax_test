<?php

namespace App\Console\Commands;

use App\Services\ImportPandascore;
use Illuminate\Console\Command;

class ImportMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Matches';
    /**
     * @var ImportPandascore
     */
    private $pandascore;


    /**
     * Create a new command instance.
     *
     * @param ImportPandascore $pandascore
     */
    public function __construct(ImportPandascore $pandascore)
    {
        parent::__construct();

        $this->pandascore = $pandascore;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->pandascore->parse();
    }
}
