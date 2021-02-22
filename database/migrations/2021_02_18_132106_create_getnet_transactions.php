<?php

use CloudFox\GetNet\Constants\TransactionTypeConstant;
use CloudFox\GetNet\Constants\TypeRegisterConstant;
use CloudFox\GetNet\Constants\StatusCodeConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetnetTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getnet_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            $table->biginteger('sale_id')->unsigned()->nullable();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('restrict');
            $table->string('adjustment_id')->nullable();
    
            $table->string('hash_id')->nullable();
            $table->string('order_id')->nullable();
    
            $table->enum('type', [
                TransactionTypeConstant::WAITING_FOR_VALID_POST,
                TransactionTypeConstant::WAITING_LIQUIDATION,
                TransactionTypeConstant::WAITING_WITHDRAWAL,
                TransactionTypeConstant::WAITING_RELEASE,
                TransactionTypeConstant::LIQUIDATED,
                TransactionTypeConstant::REVERSED,
                TransactionTypeConstant::ADJUSTMENT_CREDIT,
                TransactionTypeConstant::ADJUSTMENT_DEBIT,
                TransactionTypeConstant::PENDING_DEBIT,
                TransactionTypeConstant::WRONG,
            ]);
            /*
            Tipo de registro | 1 - Resumo da transação.
            Tipo de registro | 2 - Detalhe da transação.
            Tipo de registro | 3 - Comissão.
            Tipo de registro | 4 - Ajustes.
            Tipo de registro | 5 - Chargeback.
             * */
            
            $table->enum('type_register', [
                TypeRegisterConstant::TYPE_REGISTER_SUMMARY_TRANSACTION,
                TypeRegisterConstant::TYPE_REGISTER_DETAIL_TRANSACTION,
                TypeRegisterConstant::TYPE_REGISTER_COMMISSION,
                TypeRegisterConstant::TYPE_REGISTER_ADJUST,
                TypeRegisterConstant::TYPE_REGISTER_CHARGEBACK,
            ]);
            
            /*
            1 Crédito à vista,
            2 Crédito Parcelado Lojista,
            3 Crédito Parcelamento Administradora,
            4 Débito,
            5 Cancelamento,
            6 Chargeback,
            7 Boleto.
             * */
            //$table->string('transaction_type')->nullable();
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
             * */
            $table->enum('status_code', [
                StatusCodeConstant::STATUS_CODE_APPROVED,
                StatusCodeConstant::STATUS_CODE_WAITING,
                StatusCodeConstant::STATUS_CODE_PENDING,
                StatusCodeConstant::STATUS_CODE_PENDING_PAYMENT,
                StatusCodeConstant::STATUS_CODE_TIMEOUT,
                StatusCodeConstant::STATUS_CODE_UNDONE,
                StatusCodeConstant::STATUS_CODE_NONEXISTENT,
                StatusCodeConstant::STATUS_CODE_ADMINISTRATOR_DENIED,
                StatusCodeConstant::STATUS_CODE_RETURNED,
                StatusCodeConstant::STATUS_CODE_REPEATED,
                StatusCodeConstant::STATUS_CODE_CONCILIATION_REVERSED,
                StatusCodeConstant::STATUS_CODE_CANCELED_WITHOUT_CONFIRMATION,
                StatusCodeConstant::STATUS_CODE_DENIED_MGM,
            ])->nullable();
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->string('account_number')->nullable();
            $table->string('release_status')->nullable();
            $table->dateTime('transaction_date')->nullable(); // transaction_date || adjustment_date
            $table->dateTime('confirmation_date')->nullable();
            $table->integer('amount')->nullable(); //subseller_rate_amount || adjustment_amount
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('subseller_rate_closing_date')->nullable();
            $table->dateTime('subseller_rate_confirm_date')->nullable();
            $table->string('transaction_sign')->nullable();
            //$table->string('payment_id')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('getnet_transactions');
    }
}
