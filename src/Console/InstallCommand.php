<?php

namespace CloudFox\GetNet\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudfox:getnet-install';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare GetNetPackage for use';
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
    
        if ($this->confirm('To perform the initial configuration, we need your authorization to perform the migrations. Do you authorize us?')) {
            $this->call('migrate');
            $this->line('');
        }
    }
}