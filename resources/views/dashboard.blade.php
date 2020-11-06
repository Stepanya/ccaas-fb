<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tritel API</title>

    <!-- Tailwind CDN -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="bg-gray-300 h-100">
      <!-- Navigation -->
      <ul class="flex dashboard-navbar">
        <div class="container px-3 py-3 mx-auto">
          <li class="mx-16">
            <a class="mx-2 text-gray-900 text-xl" href="#">Tritel API</a>
          </li>
        </div>
      </ul>

      <div class="flex justify-center">
        <div class="max-w-lg rounded overflow-hidden shadow-lg bg-white my-5">
          <div class="px-6 py-4">
              <table class="table-auto">
                <thead>
                  <tr>
                    <th class="px-4 py-2">Facebook Page</th>
                    <th class="px-4 py-2">Created Contacts</th>
                    <th class="px-4 py-2">Failed Entries</th>
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

      <!-- Footer -->
      <footer class="h-100 dashboard-footer">
        <div class="container">
          <div class="px-4 py-4 my-auto text-center lg:text-left">
            <p class="footer-text px-24 mb-lg-0">&copy; 2020 Tritel Communications, Inc.</p>
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>
