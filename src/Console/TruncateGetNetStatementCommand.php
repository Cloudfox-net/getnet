<?php

namespace CloudFox\GetNet\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateGetNetStatementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudfox:getnet-truncate-data';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trunca os dados das Tabelas';
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
    
        if ($this->confirm('Você confirma limpar as tabelas?')) {
            DB::statement('TRUNCATE getnet_searches');
            DB::statement('TRUNCATE getnet_transactions');
            $this->line('');
        }
    }
}