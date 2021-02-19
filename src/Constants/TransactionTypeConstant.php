<?php


namespace CloudFox\GetNet\Constants;


class TransactionTypeConstant
{
    const WAITING_FOR_VALID_POST = 'WAITING_FOR_VALID_POST';
    const WAITING_LIQUIDATION = 'WAITING_LIQUIDATION';
    const WAITING_WITHDRAWAL = 'WAITING_WITHDRAWAL';
    const WAITING_RELEASE = 'WAITING_RELEASE';
    const LIQUIDATED = 'LIQUIDATED';
    const REVERSED = 'REVERSED';
    const ADJUSTMENT_CREDIT = 'ADJUSTMENT_CREDIT';
    const ADJUSTMENT_DEBIT = 'ADJUSTMENT_DEBIT';
    const PENDING_DEBIT = 'PENDING_DEBIT';
    const WRONG = 'WRONG';
}