@extends('layouts.app')

@section('stylesheets')
<!-- Tailwind CDN -->
<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

<!-- Custom fonts for this template -->
<!-- <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css"> -->

<!-- Custom styles for this template -->
<link href="{{ asset('tailwind_css/style.css') }}" rel="stylesheet">
@endsection

@section('index')
<div class="bg-gray-300 h-100">
  <div class="flex justify-center">
    <div class="max-w-lg rounded overflow-hidden shadow-lg bg-white my-5">
      <div class="px-6 py-4">
          <table class="table-auto">
            <thead>
              <tr>
                <th class="px-4 py-2 text-center">Facebook Page</th>
                <th class="px-4 py-2 text-center">Created Contacts</th>
                <th class="px-4 py-2 text-center">Failed Entries</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($entries as $entry)
              <tr>
                <td class="border px-4 py-2">{{ $entry->fb_page->name }}</td>
                <td class="border px-4 py-2">{{ $entry->contacts_created }}</td>
                <td class="border px-4 py-2">{{ $entry->failed_entries }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>
  </div>
</div>
@endsection
