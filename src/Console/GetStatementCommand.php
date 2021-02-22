<?php

namespace CloudFox\GetNet\Console;

use CloudFox\GetNet\Services\GetNetApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetStatementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudfox:getnet-get-statement';
    
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
        
        $companies = [
            22,
            48,
            67,
            85,
            129,
            131,
            132,
            155,
            233,
            441,
            471,
            476,
            596,
            718,
            1240,
            1521,
            1641,
            1811,
            1850,
            1902,
            1946,
            1967,
            1989,
            2104,
            2116,
            2131,
            2134,
            2139
        ];
        /*$companyId = $this->ask('Qual a company_id?', 3035);
        
        $company = DB::table('companies')
            ->select('id', 'user_id', 'fantasy_name', 'subseller_getnet_id', 'subseller_getnet_homolog_id')
            ->find($companyId);
        
        if (!$company) {
            
            $this->alert('Empresa não encontrada');
        } else {
            
            $getNetApiService = new GetNetApiService();
            $getNetApiService->getStatement($company->id);
        }*/
        $companies = DB::table('companies')
            ->select('id', 'user_id', 'fantasy_name', 'subseller_getnet_id', 'subseller_getnet_homolog_id')
            ->whereNotNull('subseller_getnet_id')
            ->where('get_net_status', 1)
            ->get();
        
        foreach ($companies as $company) {
            
            $getNetApiService = new GetNetApiService();
            $getNetApiService->getStatement($company->id);
        }
        return 0;
    }
}
