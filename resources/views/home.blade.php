@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tritel API') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <div class="form-inline mt-4">
                        <a class="btn btn-primary" href="dashboard" target="_blank">Dashboard</a>
                        <a class="btn btn-primary ml-2" href="failed-entries" target="_blank">Failed Entries</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
