@extends('layouts.app')



@section('content')
<div class="container">
    <div class="row justify-content-center">
       <!--<div class="col-md-8">-->
            <div class="card">
                <div class="card-header">myMerchants</div>

                <div class="card-body">

                    <table class="table">
                    <tr>
                        <th>merchantName</th>
                        <th>cellNo</th>
                        <th>verifyCellNo</th>
                        <th>email</th>
                        <th>agentName</th>
                        <th>payKey</th>
                        <th>memberId</th>
                    </tr>
                        @foreach($myMerchants as $myMerchant)
                            <tr>
                                <td>{{$myMerchant->merchantName}}</td>
                                <td>{{$myMerchant->cellNo}}</td>
                                <td>{{$myMerchant->verifyCellNo}}</td>
                                <td>{{$myMerchant->email}}</td>
                                <td>{{$myMerchant->agentName}}</td>
                                <td>{{$myMerchant->payKey}}</td>
                                <td>{{$myMerchant->memberId}}</td>
                            </tr>
                        @endforeach
                    </table>

                
                </div>
            </div>
        <!--</div>-->
    </div>
</div>
@endsection