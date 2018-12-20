@extends('layouts.app')



@section('content')
<div class="container">
    <div class="row justify-content-center">
       <!--<div class="col-md-8">-->
            <div class="card">
                <div class="card-header">MyTradingRecords</div>

                <div class="card-body">

                    <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>MemberID</th>
                        <th>MerchantName</th>
                        <th>MchOrderNo</th>
                        <th>Amount</th>
                        <th>TradeType</th>
                        <th>TimePaid</th>
                        <th>AddedOn</th>

                    </tr>
                        @foreach($myTradingRecords as $myTradingRecord)
                            <tr>
                                <td>{{$myTradingRecord->id}}</td>
                                <td>{{$myTradingRecord->memberId}}</td>
                                <td>{{$myTradingRecord->merchantName}}</td>
                                <td>{{$myTradingRecord->mchOrderNo}}</td>
                                <td>{{$myTradingRecord->amount}}</td>
                                <td>{{$myTradingRecord->tradeType}}</td>
                                <td>{{$myTradingRecord->timePaid}}</td>
                                <td>{{$myTradingRecord->added_on}}</td>
                            </tr>
                        @endforeach
                    </table>

                
                </div>
            </div>
        <!--</div>-->
    </div>
</div>
@endsection