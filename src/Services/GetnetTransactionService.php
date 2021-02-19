<?php


namespace CloudFox\GetNet\Services;


use Carbon\Carbon;
use CloudFox\GetNet\Constants\StatusCodeConstant;
use CloudFox\GetNet\Constants\TransactionTypeConstant;
use CloudFox\GetNet\Models\GetnetTransaction;
use Illuminate\Support\Facades\Redis;

class GetnetTransactionService
{
    
    public function save($data)
    {
        
        $getnetTransaction = GetnetTransaction::updateOrCreate(
            [
                'company_id' => $data['company_id'],
                'sale_id' => $data['sale_id'],
                'order_id' => $data['order_id'],
                'hash_id' => $data['hash_id'],
                'type' => $data['type'],
                'type_register' => $data['type_register'],
                
                'transaction_sign' => $data['transaction_sign'],
            ],
            [
                'status_code' => $data['status_code'],
                'bank' => $data['bank'],
                'agency' => $data['agency'],
                'account_number' => $data['account_number'],
                'release_status' => $data['release_status'],
                'transaction_date' => $data['transaction_date'] ? Carbon::parse($data['transaction_date'])->format('Y-m-d H:i:s') : null,
                'confirmation_date' => $data['confirmation_date'] ? Carbon::parse($data['confirmation_date'])->format('Y-m-d H:i:s') : null,
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'] ? Carbon::parse($data['payment_date'])->format('Y-m-d H:i:s') : null,
                'subseller_rate_closing_date' => $data['subseller_rate_closing_date'] ? Carbon::parse($data['subseller_rate_closing_date'])->format('Y-m-d H:i:s') : null,
                'subseller_rate_confirm_date' => $data['subseller_rate_confirm_date'] ? Carbon::parse($data['subseller_rate_confirm_date'])->format('Y-m-d H:i:s') : null,
            ]
        );
        
        if($getnetTransaction->getChanges()){
    
            //dd($getnetTransaction->getChanges(), $getnetTransaction->toArray());
            print("\r\n");
            print($getnetTransaction->id);
            print("\r\n");
            print_r($getnetTransaction->getChanges());
            print("\r\n");
        }
        /*
        $getnetTransaction->company_id = $data['company_id'] ?? null;
        $getnetTransaction->sale_id = $data['sale_id'] ?? null;
        $getnetTransaction->order_id = $data['order_id'] ?? null;
        $getnetTransaction->hash_id = $data['hash_id'] ?? null;
        $getnetTransaction->type = $data['type'] ?? null;
        $getnetTransaction->type_register = $data['type_register'] ?? null;
        
        $getnetTransaction->status_code = $data['status_code'] ?? null;
        $getnetTransaction->bank = $data['bank'] ?? null;
        $getnetTransaction->agency = $data['agency'] ?? null;
        $getnetTransaction->account_number = $data['account_number'] ?? null;
        $getnetTransaction->release_status = $data['release_status'] ?? null;
        $getnetTransaction->transaction_date = $data['transaction_date'] ?? null;
        $getnetTransaction->confirmation_date = $data['confirmation_date'] ?? null;
        $getnetTransaction->amount = $data['amount'] ?? null;
        $getnetTransaction->payment_date = $data['payment_date'] ?? null;
        $getnetTransaction->subseller_rate_closing_date = $data['subseller_rate_closing_date'] ?? null;
        $getnetTransaction->subseller_rate_confirm_date = $data['subseller_rate_confirm_date'] ?? null;
        $getnetTransaction->transaction_sign = $data['transaction_sign'] ?? null;*/
    }
    
    public function getStatus($summary, $details, $saleId)
    {
        
        $paymentDateNumeric = $details->payment_date ? Carbon::parse($details->payment_date)->format('Ymd') : null;
        $hasOrderId = empty($summary->order_id) ? false : true;
        $isTransactionCredit = $details->transaction_sign == '+';
        $isReleaseStatus = $details->release_status == 'S';
        $transactionStatusCode = StatusCodeConstant::convertToDatabase($summary->transaction_status_code);
        
        $hasValidTracking = false;
        if ($saleId) {
            
            $hasValidTracking = (boolean)Redis::connection('redis-statement')->get("sale:has:tracking:{$saleId}");
        }
        
        if ($hasOrderId && !$isTransactionCredit && $transactionStatusCode == StatusCodeConstant::STATUS_CODE_APPROVED) {
            
            return TransactionTypeConstant::REVERSED;
            
        } elseif ($hasOrderId && $isTransactionCredit && !$isReleaseStatus && !$hasValidTracking) {
            
            return TransactionTypeConstant::WAITING_FOR_VALID_POST;
            
        } elseif ($hasOrderId && $isTransactionCredit && $hasValidTracking && !$isReleaseStatus && $paymentDateNumeric && ($paymentDateNumeric > date('Ymd'))) {
            
            return TransactionTypeConstant::WAITING_RELEASE;
        } elseif ($hasOrderId && $isTransactionCredit && $hasValidTracking && !$isReleaseStatus) {
            
            return TransactionTypeConstant::WAITING_WITHDRAWAL;
        } elseif (
            (
                $hasOrderId && $isTransactionCredit && $hasValidTracking && $isReleaseStatus && empty($subSellerRateConfirmDate)
            )
            ||
            (
                $transactionStatusCode == StatusCodeConstant::STATUS_CODE_RETURNED && empty($subSellerRateConfirmDate)
            )
        ) {
            
            return TransactionTypeConstant::WAITING_LIQUIDATION;
        } elseif (
            
            (
                $hasOrderId && $isTransactionCredit && $hasValidTracking && !empty($subSellerRateConfirmDate)
                && in_array($transactionStatusCode,
                    [
                        StatusCodeConstant::STATUS_CODE_APPROVED,
                        StatusCodeConstant::STATUS_CODE_RETURNED
                    ])
            )
            ||
            (
                $transactionStatusCode == StatusCodeConstant::STATUS_CODE_RETURNED && !empty($subSellerRateConfirmDate)
            )
        ) {
            
            return TransactionTypeConstant::LIQUIDATED;
        } else {
            
            return TransactionTypeConstant::WRONG;
        }
    }
}