<?php
date_default_timezone_set("Asia/Shanghai");
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table){

            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('permission');

        });

        Schema::create('myEbankTrading', function(Blueprint $table){

            $table->increments('id');
            $table->string('myMerchantID');
            $table->string('merchantName');
            $table->string('myOrderNo')->unique(); //myMerchantID+orderID defined by customers
            $table->string('tradeType');
            $table->string('totalAmount');  //customer pay amount
            $table->string('settleAmount'); //discount amount
            $table->string('balance'); 
            $table->string('payTime');  //order pay succeed time replied by sandypay 
            $table->timestampTz('added_on'); //creating time of the order sent from customer 
        });

        Schema::create('myMerchant', function(Blueprint $table){

            $table->increments('id');
            $table->string('merchantName')->unique();
            $table->string('cellNo');
            $table->string('verifyCellNo');
            $table->string('email')->unique();
            $table->string('agentName')->nullable();
            $table->string('payKey')->unique();
            $table->string('memberId')->unique();
            $table->string('mid')->unique();
            $table->string('payChannel')->unique();
            
        });


        Schema::create('sandpayEbankNotify', function(Blueprint $table){
            
            $table->increments('id');
            $table->string('mid');
            $table->string('orderCode');
            $table->string('totalAmount');
            $table->string('orderStatus');
            $table->string('traceNo');
            $table->string('buyerPayAmount');
            $table->string('discAmount');
            $table->string('payTime');
            $table->string('clearDate');
            //$table->string('refundAmount')->nullable();

        });

        Schema::create('sandpayMerchant', function(Blueprint $table){

            $table->increments('id');
            $table->string('mid')->unique();
            //$table->string('memberId')->unique();
            $table->string('certPwd')->unique();
            $table->string('priKey')->unique();
            $table->string('pubKey')->unique();


        });

        Schema::create('sandpayEbankTrading', function(Blueprint $table){

            $table->increments('id');
            $table->string('orderCode')->unique();
            $table->string('totalAmount');
            $table->string('credential')->default('none');
            $table->string('traceNo')->default('none');

            $table->string('buyerPayAmount')->default('none');
            $table->string('disAmount')->default('none');
            $table->string('payTime')->default('none');
            $table->string('clearDate')->default('none');

            $table->longText('finalBankLink', 500)->unique();
            $table->string('mid');
        });

        Schema::create('zgMerchant', function(Blueprint $table){

            $table->increments('id');
            $table->string('mid')->unique();
            $table->string('priKey')->unique();
            $table->string('pubKey')->unique();
            $table->string('priKeyPwd')->unique();
            $table->string('md5Str')->unique();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('users');
        Schema::dropIfExists('myTradingRecord');
        Schema::dropIfExists('myMerchant');
    }



}
