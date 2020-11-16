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
  @isset($entries)
  <div class="bg-gray-300 min-h-screen">
    <div class="flex justify-center items-center">
  @else
  <div class="bg-gray-300 min-h-full">
    <div class="min-h-screen flex flex-col justify-center items-center">
  @endisset
      @if (session('success'))
        <div class="flex items-center bg-green-500 w-1/2 text-white text-sm font-bold px-4 py-3" role="alert">
          <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="flex items-center bg-yellow-500 w-1/2 text-white text-sm font-bold px-4 py-3" role="alert">
          <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
          {{ session('error') }}
        </div>
      @endif
      @isset($entries)
        <div class="w-1/2 rounded overflow-hidden shadow-lg bg-white mt-5">
      @else
        <div class="w-1/2 rounded overflow-hidden shadow-lg bg-white mt-2">
      @endisset
          <div class="px-6 py-4">
            <form class="search-form" action="/search-entries" method="post">
              {{ csrf_field() }}
              <div class="flex flex-wrap -mx-3 mb-2">
                  <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                      Region
                    </label>
                    <div class="relative">
                      <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" name="region_id">
                        @foreach ($regions as $region)
                          <option value="{{$region->id}}" @isset($prev_id) @if($region->id == $prev_id) selected @endif @endisset>{{ $region->name }}</option>
                        @endforeach
                      </select>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                    </div>
                  </div>
                  <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                      Date
                    </label>
                    <div class="relative">
                      <input class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" type="date" name="created_date" @isset($prev_date) value="{{$prev_date}}" @endisset></input>
                      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                      </div>
                    </div>
                  </div>
                  <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0 flex justify-end items-end">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white focus:outline-none font-bold py-3 px-10 rounded-full">
                      Search
                    </button>
                  </div>
                </div>
            </form>
          </div>
        </div>
    </div>
    @isset($entries)
      <div class="flex justify-center">
        <div class="w-1/2 rounded overflow-hidden flex justify-center shadow-lg bg-white my-2">
          <div class="px-6 py-4">
              <table class="table-auto">
                <thead>
                  <tr>
                    <th class="px-12 py-2 text-center">Facebook Page</th>
                    <th class="px-12 py-2 text-center">Created Contacts</th>
                    <th class="px-12 py-2 text-center">Failed Entries</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($entries as $entry)
                  <tr>
                    <td class="border px-12 py-2">{{ $entry->fb_page->name }}</td>
                    <td class="border px-12 py-2">{{ $entry->contacts_created }}</td>
                    <td class="border px-12 py-2">{{ $entry->failed_entries }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
      </div>
    @endisset
  </div>
@endsection
