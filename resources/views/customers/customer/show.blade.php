@extends('partials.template')
@section('main')
<div class="app-content content ">
    <div class="container">
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">customer {{ $customer->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/customers/index/') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        {{-- <a href="{{ url('/admin/customers/' . $customer->id . '/edit') }}" title="Edit customer"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('admin/customer' . '/' . $customer->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete customer" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form> --}}
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $customer->id }}</td>
                                    </tr>
                                    <tr>
                                        <th> Alamat </th>
                                        <td> {{ $customer->alamat }} </td>
                                    </tr>
                                    <tr>
                                        <th> Facebook </th>
                                        <td> {{ $customer->facebook }} </td>
                                    </tr>
                                    <tr>
                                        <th> Instagram </th>
                                        <td> {{ $customer->instagram }} </td>
                                    </tr>
                                    <tr>
                                        <th> Whatsapp </th>
                                        <td> {{ $customer->whatsapp }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
