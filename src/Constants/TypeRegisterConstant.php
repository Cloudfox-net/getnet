<?php


namespace CloudFox\GetNet\Constants;


class TypeRegisterConstant
{
    
    const TYPE_REGISTER_SUMMARY_TRANSACTION = 'TRANSACTION_SUMMARY';
    const TYPE_REGISTER_DETAIL_TRANSACTION = 'TRANSACTION_DETAIL';
    const TYPE_REGISTER_COMMISSION = 'COMMISSION';
    const TYPE_REGISTER_ADJUST = 'ADJUST';
    const TYPE_REGISTER_CHARGEBACK = 'CHARGEBACK';
    
    const TYPE_REGISTER_SUMMARY_TRANSACTION_GETNET = 1;
    const TYPE_REGISTER_DETAIL_TRANSACTION_GETNET = 2;
    const TYPE_REGISTER_COMMISSION_GETNET = 3;
    const TYPE_REGISTER_ADJUST_GETNET = 4;
    const TYPE_REGISTER_CHARGEBACK_GETNET = 5;
}