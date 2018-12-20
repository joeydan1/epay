@extends('layouts.app')



@section('content')
<div class="container">
    <div class="row justify-content-center">
       <!--<div class="col-md-8">-->
            <div class="card">
                <div class="card-header">sandpayMerchants</div>

                <div class="card-body">

                    <table class="table">
                    <tr>
                        <th>SandpayID</th>
                        <th>MemberID</th>
                        <th>PubKey</th>
                        <th>PriKey</th>
                    </tr>
                        @foreach($sandpayMerchant as $sandpayMerchant)
                            <tr>
                                <td>{{$sandpayMerchant->mid}}</td>
                                <td>{{$sandpayMerchant->memberId}}</td>
                                <td>{{$sandpayMerchant->pubKey}}</td>
                                <td>{{$sandpayMerchant->priKey}}</td>
                            </tr>
                        @endforeach
                    </table>

                
                </div>
            </div>
        <!--</div>-->
    </div>
</div>
@endsection