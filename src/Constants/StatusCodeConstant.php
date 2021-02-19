<?php


namespace CloudFox\GetNet\Constants;


class StatusCodeConstant
{
    
    /*
    0 Aprovado,
    70 Aguardando,
    77 Pendente,
    78 Pendente Pagamento,
    83 Timeout,
    86 Desfeita,
    90 Inexistente,
    91 Negado - Administradora,
    92 Estornada,
    93 Repetida,
    94 Estornada Conciliacao,
    98 Cancelada - Sem Confirmacao,
    99 Negado - MGM.
    
    APPROVED
    WAITING
    PENDING
    PENDING_PAYMENT
    TIMEOUT
    UNDONE
    NONEXISTENT
    ADMINISTRATOR_DENIED
    RETURNED
    REPEATED
    CONCILIATION_REVERSED
    CANCELED_WITHOUT_CONFIRMATION
    DENIED_MGM
     * */
    
    const STATUS_CODE_APPROVED = 'APPROVED';
    const STATUS_CODE_WAITING = 'WAITING';
    const STATUS_CODE_PENDING = 'PENDING';
    const STATUS_CODE_PENDING_PAYMENT = 'PENDING_PAYMENT';
    const STATUS_CODE_TIMEOUT = 'TIMEOUT';
    const STATUS_CODE_UNDONE = 'UNDONE';
    const STATUS_CODE_NONEXISTENT = 'NONEXISTENT';
    const STATUS_CODE_ADMINISTRATOR_DENIED = 'ADMINISTRATOR_DENIED';
    const STATUS_CODE_RETURNED = 'RETURNED';
    const STATUS_CODE_REPEATED = 'REPEATED';
    const STATUS_CODE_CONCILIATION_REVERSED = 'CONCILIATION_REVERSED';
    const STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION = 'CANCELED_WITHOUT_CONFIRMATION';
    const STATUS_CODE_DENIED_MGM = 'DENIED_MGM';
    
    const STATUS_CODE_APPROVED_GETNET = 0;
    const STATUS_CODE_WAITING_GETNET = 70;
    const STATUS_CODE_PENDING_GETNET = 77;
    const STATUS_CODE_PENDING_PAYMENT_GETNET = 78;
    const STATUS_CODE_TIMEOUT_GETNET = 83;
    const STATUS_CODE_UNDONE_GETNET = 86;
    const STATUS_CODE_NONEXISTENT_GETNET = 90;
    const STATUS_CODE_ADMINISTRATOR_DENIED_GETNET = 91;
    const STATUS_CODE_RETURNED_GETNET = 92;
    const STATUS_CODE_REPEATED_GETNET = 93;
    const STATUS_CODE_CONCILIATION_REVERSED_GETNET = 94;
    const STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION_GETNET = 98;
    const STATUS_CODE_DENIED_MGM_GETNET = 99;
    
    public static function convertToDatabase($statusCode)
    {
        
        /*if($statusCode == self::STATUS_CODE_APPROVED_GETNET){
            
            return self::STATUS_CODE_APPROVED;
        }
        else if($statusCode == self::STATUS_CODE_WAITING_GETNET){
            return self::STATUS_CODE_WAITING;
        }
        else if($statusCode == self::STATUS_CODE_PENDING_GETNET){
            return self::STATUS_CODE_PENDING;
        }
        else if($statusCode == self::STATUS_CODE_PENDING_PAYMENT_GETNET){
            return self::STATUS_CODE_PENDING_PAYMENT;
        }
        else if($statusCode == self::STATUS_CODE_TIMEOUT_GETNET){
            return self::STATUS_CODE_TIMEOUT;
        }
        else if($statusCode == self::STATUS_CODE_UNDONE_GETNET){
            return self::STATUS_CODE_UNDONE;
        }
        else if($statusCode == self::STATUS_CODE_NONEXISTENT_GETNET){
            return self::STATUS_CODE_NONEXISTENT;
        }
        else if($statusCode == self::STATUS_CODE_ADMINISTRATOR_DENIED_GETNET){
            return self::STATUS_CODE_ADMINISTRATOR_DENIED;
        }
        else if($statusCode == self::STATUS_CODE_RETURNED_GETNET){
            return self::STATUS_CODE_RETURNED;
        }
        else if($statusCode == self::STATUS_CODE_REPEATED_GETNET){
            return self::STATUS_CODE_REPEATED;
        }
        else if($statusCode == self::STATUS_CODE_CONCILIATION_REVERSED_GETNET){
            return self::STATUS_CODE_CONCILIATION_REVERSED;
        }
        else if($statusCode == self::STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION_GETNET){
            return self::STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION;
        }
        else if($statusCode == self::STATUS_CODE_DENIED_MGM_GETNET){
            return self::STATUS_CODE_DENIED_MGM;
        }*/
        switch ($statusCode) {
            case self::STATUS_CODE_APPROVED_GETNET:
                return self::STATUS_CODE_APPROVED;
            case self::STATUS_CODE_WAITING_GETNET:
                return self::STATUS_CODE_WAITING;
            case self::STATUS_CODE_PENDING_GETNET:
                return self::STATUS_CODE_PENDING;
            case self::STATUS_CODE_PENDING_PAYMENT_GETNET:
                return self::STATUS_CODE_PENDING_PAYMENT;
            case self::STATUS_CODE_TIMEOUT_GETNET:
                return self::STATUS_CODE_TIMEOUT;
            case self::STATUS_CODE_UNDONE_GETNET:
                return self::STATUS_CODE_UNDONE;
            case self::STATUS_CODE_NONEXISTENT_GETNET:
                return self::STATUS_CODE_NONEXISTENT;
            case self::STATUS_CODE_ADMINISTRATOR_DENIED_GETNET:
                return self::STATUS_CODE_ADMINISTRATOR_DENIED;
            case self::STATUS_CODE_RETURNED_GETNET:
                return self::STATUS_CODE_RETURNED;
            case self::STATUS_CODE_REPEATED_GETNET:
                return self::STATUS_CODE_REPEATED;
            case self::STATUS_CODE_CONCILIATION_REVERSED_GETNET:
                return self::STATUS_CODE_CONCILIATION_REVERSED;
            case self::STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION_GETNET:
                return self::STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION;
            case self::STATUS_CODE_DENIED_MGM_GETNET:
                return self::STATUS_CODE_DENIED_MGM;
        }
    }
}