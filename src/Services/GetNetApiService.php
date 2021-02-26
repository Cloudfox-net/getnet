<?php


namespace CloudFox\GetNet\Services;


use Carbon\Carbon;
use CloudFox\GetNet\Constants\StatusCodeConstant;
use CloudFox\GetNet\Constants\TypeRegisterConstant;
use CloudFox\GetNet\Http\Requests\OptionsRequest;
use CloudFox\GetNet\Models\GetnetSearch;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class GetNetApiService
{
    
    protected array $errors = [];
    private string $authorizationBasic;
    private string $bearerToken;
    private string $apiUrl = 'https://api-backoffice.getnet.com.br';
    private string $sellerId;
    private string $merchantId;
    /**
     * @var GetnetSearch
     */
    private GetnetSearch $getnetSearch;
    private ?string $subSellerId = null;
    private int $companyId;
    
    public function __construct()
    {
        
        $this->authorizationBasic = base64_encode(
            getenv('GET_NET_CLIENT_ID_PRODUCTION') . ':' . getenv('GET_NET_CLIENT_SECRET_PRODUCTION')
        );
        $this->sellerId = getenv('GET_NET_SELLER_ID_PRODUCTION');
        $this->merchantId = getenv('GET_NET_MERCHANT_ID_PRODUCTION');
    }
    
    public function getStatement(int $companyId)
    {
        
        $company = DB::table('companies')->find($companyId);
        
        $this->getAccessToken();
        //$subseller_getnet_id
        //$subseller_getnet_homolog_id
        $this->subSellerId = $company->subseller_getnet_id;
        $this->companyId = $company->id;
        
        $optionsRequest = new OptionsRequest();
        $optionsRequest->url = $this->apiUrl . '/v1/mgm/statement';
        $optionsRequest->method = OptionsRequest::METHOD_GET;
        
        $requestedAt = Carbon::now();
        $startDate = '2020-07-01';
        $endDate = Carbon::today()->addDays(40)->format('Y-m-d');
        
        $startDate .= ' 00:00:00';
        $endDate .= ' 23:59:59';
        
        $optionsRequest->headers = [
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearerToken,
            ]
            ,
            'query' => [
                'seller_id' => $this->sellerId,
                'subseller_id' => $this->subSellerId,
                'schedule_date_init' => $startDate,
                'schedule_date_end' => $endDate,
            ],
        ];
        
        $requestService = new RequestService();
        
        try {
            
            $this->getnetSearch = new GetnetSearch();
            $this->getnetSearch->company_id = $companyId;
            $this->getnetSearch->subseller_id = $this->subSellerId;
            $this->getnetSearch->query_params = json_encode($optionsRequest->headers['query']);
            $this->getnetSearch->requested_at = $requestedAt;
            $this->getnetSearch->ended_at = null;
            $this->getnetSearch->time_get_api_data = null;
            $this->getnetSearch->time_script_execution = null;
            $this->getnetSearch->list_transactions_count = null;
            $this->getnetSearch->list_transactions_node = null;
            $this->getnetSearch->commission_count = null;
            $this->getnetSearch->commission_node = null;
            $this->getnetSearch->adjustments_count = null;
            $this->getnetSearch->adjustments_node = null;
            $this->getnetSearch->chargeback_count = null;
            $this->getnetSearch->chargeback_node = null;
            
            $this->getnetSearch->save();
            
            $data = $requestService->sendRequest($optionsRequest);
            
            $this->getnetSearch->time_get_api_data = Carbon::now()->diffInSeconds($requestedAt);
            $this->getnetSearch->save();
            
            if (isset($data->list_transactions)) {
                
                $this->saveFromListTransaction($data->list_transactions);
            }
            
            if (isset($data->adjustments)) {
                
                $this->saveFromAdjustment($data->adjustments);
            }
            
            $this->getnetSearch->ended_at = Carbon::now();
            $this->getnetSearch->time_script_execution = Carbon::now()->diffInSeconds($this->getnetSearch->created_at);
            $this->getnetSearch->save();
            
        } catch (Exception $exception) {
            
            //Log::error();
            
            print_r("\r\n" . $exception->getMessage() . "\r\n");
            Log::error($exception->getMessage(), $exception->getTrace());
            
        } catch (GuzzleException $exception) {
            
            print_r("\r\n" . $exception->getMessage() . "\r\n");
            Log::error($exception->getMessage(), $exception->getTrace());
        }
    }
    
    private function getAccessToken()
    {
        
        $optionsRequest = new OptionsRequest();
        $optionsRequest->url = $this->apiUrl . '/credenciamento/auth/oauth/v2/token';
        $optionsRequest->method = OptionsRequest::METHOD_POST;
        
        $optionsRequest->headers = [
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $this->authorizationBasic,
            ]
            ,
            'form_params' => [
                'scope' => 'mgm',
                'grant_type' => 'client_credentials',
            ],
        ];
        
        $requestService = new RequestService();
        
        try {
            
            $data = $requestService->sendRequest($optionsRequest);
            $this->bearerToken = $data->access_token;
            
        } catch (Exception $exception) {
            print_r("\r\n" . $exception->getMessage() . "\r\n");
            Log::error($exception->getMessage(), $exception->getTrace());
            
        } catch (GuzzleException $exception) {
            
            print_r("\r\n" . $exception->getMessage() . "\r\n");
            Log::error($exception->getMessage(), $exception->getTrace());
        }
    }
    
    private function saveFromListTransaction($listTransactions)
    {
        
        
        /*if ($this->subSellerId) {
            
            $this->getnetSearch->list_transactions_node = json_encode($listTransactions);
        } else {
            
            $this->getnetSearch->list_transactions_node = 'IGNORANDO';
        }*/
        
        $this->getnetSearch->list_transactions_count = count($listTransactions);
        
        $this->getnetSearch->save();
        
        $limit = $this->getnetSearch->list_transactions_count / 20;
        $count = 0;
        $percentage = 0;
        
        print_r(' - Percorrer ' . $this->getnetSearch->list_transactions_count . ' list_transactions' . "\r\n");
        foreach ($listTransactions as $transaction) {
            
            $count++;
            
            if ($count >= $limit) {
                
                $count = 0;
                $percentage += 5;
                
                print_r(' - ' . $percentage . '%' . "                   ");
            }
            
            /*
"list_transactions": [
    {
        "summary": {
            "type_register": 0,
            "order_id": "string",
            "seller_id": "string",
            "marketplace_subsellerid": "string",
            "merchand_id": "string",
            "cnpj_marketplace": "string",
            "marketplace_transaction_id": 0,
            "transaction_date": "2021-02-18T19:52:34Z",
            "confirmation_date": "2021-02-18T19:52:34Z",
            "product_id": 0,
            "transaction_type": 0,
            "number_installments": 0,
            "nsu_host": "string",
            "acquirer_transaction_id": "string",
            "card_payment_amount": 0,
            "sum_details_card_payment_amount": 0,
            "marketplace_original_transaction_id": 0,
            "transaction_status_code": 0,
            "transaction_sign": "string",
            "terminal_nsu": "string",
            "reason_message": "string",
            "authorization_code": "string",
            "payment_id": "string",
            "terminal_identification": "string",
            "nsu_tef": "string",
            "entry_mode": "string",
            "transaction_channel": "string",
            "capture": "string",
            "payment_tag": "string"
        },
        "details": [
            {
                "type_register": 0,
                "bank": "string",
                "agency": "string",
                "account_number": "string",
                "marketplace_schedule_id": 0,
                "marketplace_subsellerid": "string",
                "nu_liquid": "string",
                "release_status": "string",
                "merchand_id": "string",
                "cpfcnpj_subseller": "string",
                "cancel_custom_key": "string",
                "cancel_request_id": "string",
                "marketplace_transaction_id": 0,
                "cnpj_marketplace": "string",
                "transaction_date": "2021-02-18T19:52:34Z",
                "confirmation_date": "2021-02-18T19:52:34Z",
                "item_id": "string",
                "number_installments": 0,
                "installment": 0,
                "installment_date": "2021-02-18T19:52:34Z",
                "installment_amount": 0,
                "subseller_rate_amount": 0,
                "subseller_rate_percentage": 0,
                "payment_date": "2021-02-18T19:52:34Z",
                "subseller_rate_closing_date": "2021-02-18T19:52:34Z",
                "subseller_rate_confirm_date": "2021-02-18T19:52:34Z",
                "subseller_id": 0,
                "seller_id": "string",
                "transaction_sign": "string",
                "item_id_mgm": "string",
                "payment_id": "string",
                "payment_tag": "string",
                "item_split_tag": "string"
            }
        ]
    }
],
             * */
            
            /*$sale_id = null;
            $company_id = null;
            $order_id = null;
            $hash_id = null;
            $type = null;
            $type_register = null;
            $status_code = null;
            $bank = null;
            $agency = null;
            $account_number = null;
            $release_status = null;
            $transaction_date = null;
            $confirmation_date = null;
            $amount = null;
            $payment_date = null;
            $subseller_rate_closing_date = null;
            $subseller_rate_confirm_date = null;
            $transaction_sign = null;
            
            $getnetTransaction = new GetnetTransaction();
            $getnetTransaction->statement_search_id = $this->getnetSearch->id;*/
            $getnetTransactionService = new GetnetTransactionService();
            
            $details = $transaction->details[0];
            
            $company_id = $this->companyId;
            $order_id = $transaction->summary->order_id;
            $sale_id = $this->getHashIdFromOrderId($order_id)['sale_id'];
            $hash_id = $this->getHashIdFromOrderId($order_id)['hash_id'];
            $type = $getnetTransactionService->getStatus($transaction->summary, $details, $sale_id);
            //$type = TransactionTypeConstant::WRONG;
            $type_register = TypeRegisterConstant::TYPE_REGISTER_DETAIL_TRANSACTION;
            $status_code = StatusCodeConstant::convertToDatabase($transaction->summary->transaction_status_code);
            $bank = $details->bank;
            $agency = $details->agency;
            $account_number = $details->account_number;
            $release_status = $details->release_status;
            $transaction_date = $details->transaction_date;
            $confirmation_date = $details->confirmation_date;
            $amount = $details->subseller_rate_amount;
            $payment_date = $details->payment_date;
            $subseller_rate_closing_date = $details->subseller_rate_closing_date;
            $subseller_rate_confirm_date = $details->subseller_rate_confirm_date;
            $transaction_sign = $details->transaction_sign;
            $description = 'Transação #' . $hash_id;
            
            (new GetnetTransactionService())->save([
                'company_id' => $company_id,
                'sale_id' => $sale_id,
                'order_id' => $order_id,
                'hash_id' => $hash_id,
                'type' => $type,
                'type_register' => $type_register,
                'status_code' => $status_code,
                'bank' => $bank,
                'agency' => $agency,
                'account_number' => $account_number,
                'release_status' => $release_status,
                'transaction_date' => $transaction_date,
                'confirmation_date' => $confirmation_date,
                'amount' => $amount,
                'payment_date' => $payment_date,
                'subseller_rate_closing_date' => $subseller_rate_closing_date,
                'subseller_rate_confirm_date' => $subseller_rate_confirm_date,
                'transaction_sign' => $transaction_sign,
                'description' => $description,
            ]);
            
            /*if (isset($transaction->summary)) {
                
                $hash_id = $this->getHashIdFromOrderId($transaction->summary->order_id)['hash_id'];
                $sale_id = $this->getHashIdFromOrderId($transaction->summary->order_id)['sale_id'];
                
                
                $getnetTransaction->hash_id = $hash_id;
                $getnetTransaction->sale_id = $sale_id;
            }
            
            $getnetTransaction->save();
            
            if (isset($transaction->details)) {
                
                foreach ($transaction->details as $detail) {
                    
                    $getnetTransactionDetail = new StatementTransactionDetail();
                    $getnetTransactionDetail->statement_search_id = $this->getnetSearch->id;
                    $getnetTransactionDetail->statement_transaction_id = $getnetTransaction->id;
                    $getnetTransactionDetail->statement_sale_id = $sale_id;
                    $getnetTransactionDetail->hash_id = $hash_id;
                    $getnetTransactionDetail->fill((array)$detail);
                    $getnetTransactionDetail->save();
                }
            }*/
        }
    }
    
    private function getHashIdFromOrderId($order_id)
    {
        
        if ($order_id) {
            
            $parts = explode('-', $order_id);
            $hashId = $parts[0];
            $saleId = current(Hashids::connection('sale_id')->decode($parts[0]));
        } else {
            
            $hashId = $saleId = null;
        }
        
        return ['hash_id' => $hashId, 'sale_id' => $saleId];
    }
    
    private function saveFromAdjustment($adjustments)
    {
        
        /*if ($subseller_id) {
            
            $this->getnetSearch->adjustments_node = json_encode($data->adjustments);
        } else {
            
            $this->getnetSearch->adjustments_node = 'IGNORANDO';
        }*/
        
        $this->getnetSearch->adjustments_count = count($adjustments);
        
        $this->getnetSearch->save();
        
        $limit = $this->getnetSearch->adjustments_count / 20;
        $count = 0;
        $percentage = 0;
        print_r("\r\n" . ' - Percorrer ' . $this->getnetSearch->adjustments_count . ' adjustments' . "\r\n");
        
        /*
            +"type_register": 4
            +"bank": "77"
            +"agency": "1"
            +"account_number": "9046167-3"
            +"account_type": "C"
            +"marketplace_subsellerid": "700116896"
            +"adjustment_origin": "M"
            +"marketplace_schedule_id": 107373656
            +"nu_liquid": "202101200000849576960"
            +"merchand_id": "7762088"
            +"cpfcnpj_subseller": "36492375000163"
            +"cnpj_marketplace": "34109335000164"
            +"adjustment_id": 1191211
            +"adjustment_type": 2
            +"adjustment_date": "2021-01-07T16:03:35"
            +"adjustment_amount": 1000
            +"subseller_rate_closing_date": "2021-01-20T00:00:00"
            +"subseller_rate_confirm_date": "2021-01-21T00:00:00"
            +"payment_date": "2021-01-11T00:00:00"
            +"transaction_sign": "-"
            +"adjustment_reason": "Teste com Júlio"
            +"order_id": null
            +"product_id": 0
            +"our_number": null
            +"nsu_boleto_adjustment": null
        */
        
        $getnetTransactionService = new GetnetTransactionService();
        
        foreach ($adjustments as $adjustment) {
            
            $count++;
            
            if ($count >= $limit) {
                
                $count = 0;
                $percentage += 5;
                
                print_r(' - ' . $percentage . '%' . "                   ");
            }
            
            $company_id = $this->companyId;
            $order_id = $adjustment->order_id;
            $sale_id = $this->getHashIdFromOrderId($order_id)['sale_id'];
            $hash_id = $this->getHashIdFromOrderId($order_id)['hash_id'];
            $type = $getnetTransactionService->getAdjustmentStatus($adjustment->transaction_sign);
            //$type = TransactionTypeConstant::WRONG;
            $type_register = TypeRegisterConstant::TYPE_REGISTER_ADJUST;
            $status_code = null;
            $bank = $adjustment->bank;
            $agency = $adjustment->agency;
            $account_number = $adjustment->account_number;
            $release_status = null;
            $transaction_date = $adjustment->adjustment_date;
            $confirmation_date = null;
            $amount = $adjustment->adjustment_amount;
            $payment_date = $adjustment->payment_date;
            $subseller_rate_closing_date = $adjustment->subseller_rate_closing_date;
            $subseller_rate_confirm_date = $adjustment->subseller_rate_confirm_date;
            $transaction_sign = $adjustment->transaction_sign;
            $adjustment_id = $adjustment->adjustment_id;
            $description = $adjustment->adjustment_reason;
            
            (new GetnetTransactionService())->saveAdjustments([
                'company_id' => $company_id,
                'sale_id' => $sale_id,
                'order_id' => $order_id,
                'hash_id' => $hash_id,
                'type' => $type,
                'type_register' => $type_register,
                'status_code' => $status_code,
                'bank' => $bank,
                'agency' => $agency,
                'account_number' => $account_number,
                'release_status' => $release_status,
                'transaction_date' => $transaction_date,
                'confirmation_date' => $confirmation_date,
                'amount' => $amount,
                'payment_date' => $payment_date,
                'subseller_rate_closing_date' => $subseller_rate_closing_date,
                'subseller_rate_confirm_date' => $subseller_rate_confirm_date,
                'transaction_sign' => $transaction_sign,
                'adjustment_id' => $adjustment_id,
                'description' => $description,
            ]);
        }
    }
}