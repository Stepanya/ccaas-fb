@extends('layouts.app')

@section('stylesheets')
<!-- Custom fonts for this template -->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
<!-- Custom styles for this template -->
<link href="{{ asset('tailwind_css/style.css') }}" rel="stylesheet">
@endsection

@section('index')
  <!-- Masthead -->
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">Tritel API</h1>
        </div>
      </div>
    </div>
  </header>
@endsection
