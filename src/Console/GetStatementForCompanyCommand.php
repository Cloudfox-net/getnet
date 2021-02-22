<?php

namespace CloudFox\GetNet\Console;

use CloudFox\GetNet\Services\GetNetApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetStatementForCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudfox:getnet-get-statement-for-company';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza extrato para uma empresa';
    
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
     * @return int
     */
    public function handle()
    {
        
        $companyId = $this->ask('Qual a company_id?', 3035);
        
        $company = DB::table('companies')
            ->select('id', 'user_id', 'fantasy_name', 'subseller_getnet_id', 'subseller_getnet_homolog_id')
            ->find($companyId);
        
        if (!$company) {
            
            $this->alert('Empresa não encontrada');
        } else {
            
            $getNetApiService = new GetNetApiService();
            $getNetApiService->getStatement($company->id);
        }
        
        return 0;
    }
}
