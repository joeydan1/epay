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
                        <th>mid</th>
                        <th>orderCode</th>
                        <th>totalAmount</th>
                        <th>credential</th>
                        <th>traceNo</th>
                        <th>buyerPayAmount</th>
                        <th>disAmount</th>
                        <th>payTime</th>
                        <th>clearDate</th>
                        <th>finalBankLink</th>
                        
                    

                    </tr>
                        @foreach($records as $record)
                            <tr>
                                <td>{{$record->id}}</td>
                                <td>{{$record->mid}}</td>
                                <td>{{$record->orderCode}}</td>
                                <td>{{$record->totalAmount}}</td>
                                <td>{{$record->credential}}</td>
                                <td>{{$record->traceNo}}</td>
                                <td>{{$record->buyerPayAmount}}</td>
                                <td>{{$record->disAmount}}</td>
                                <td>{{$record->payTime}}</td>
                                <td>{{$record->clearDate}}</td>
                                <td>{{$record->finalBankLink}}</td>
                            </tr>
                        @endforeach
                    </table>

                
                </div>
            </div>
        <!--</div>-->
    </div>
</div>
@endsection