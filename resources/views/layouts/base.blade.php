<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="{{ asset('style.css') }}" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
  <title>@yield('title')</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
  <meta name="robots" content="noindex, nofollow">
  <meta name="robots" content="noimageindex">
  <meta name="googlebot" content="noindex, nofollow">


</head>
<style>
  ::selection {
    background-color: #C27803;
    color: aliceblue;
  }
</style>
<body class="bg-amber-50">
  <div class="flex-center position-ref full-height">

    <div class="top-right links">
      @auth
      <nav class="bg-white px-2 sm:px-4 py-2.5  fixed w-full z-20 top-0 left-0 border-b border-gray-200">
        <div class="container flex flex-wrap items-center justify-between mx-auto">
          <a href="{{route('home')}}"><span class="self-center text-xl font-semibold whitespace-nowrap ">Joach'Autrement</span></a>
          </a>
          <div class="flex md:order-2">
            <button onclick="location.href= `{{ route('logout')}}`" type="button" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0 ">Deconnexion</button>
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 " aria-controls="navbar-sticky" aria-expanded="false">
              <span class="sr-only">Ouvrir le menu</span>
              <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
          <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white">
            @if (Auth::user()->idPerm == 1)
              <li>
                <a href="{{ route('settingsView') }} " class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-yellow-700 md:p-0">Paramètres</a>
              </li>
              @endif
              @if (Auth::user()->idPerm == 1 || Auth::user()->idPerm == 2)
              <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownGestion" class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-yellow-700 md:p-0 md:w-auto">Gestion<svg class="w-4 h-4 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg></button>
              <!-- Dropdown menu -->
              <div id="dropdownGestion" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownLargeButton">
                  <li>
                    <p  class="font-semibold select-none block px-4 py-2 text-sm text-gray-700">Évènement</p>
                  </li>
                  <li>
                    <a href="{{route('allActivite')}}" class="block px-8 py-2 hover:bg-gray-100">Activités</a>
                  </li>
                  <li>
                    <a href="{{route('allCategories')}}" class="block px-8 py-2 hover:bg-gray-100">Catégories</a>
                  </li>
                  
                  <li>
                    <a href="{{route('allIntervenant')}}" class="block px-8 py-2 hover:bg-gray-100">Intervenants</a>
                  </li>
                  
                </ul>
                @if (Auth::user()->idPerm == 1 )
                <div class="py-1">
                    <p class=" font-semibold select-none block px-4 py-2 text-sm text-gray-700">Comptes</p>
                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownLargeButton">
                    <li>
                    <a href="{{route('ViewCreateEAccount')}}" class="block px-8 py-2 hover:bg-gray-100">Élèves</a>
                  </li>
                  <li>
                    <a href="{{route('ViewCreateEAccount')}}" class="block px-8 py-2 hover:bg-gray-100">Professeurs</a>
                  </li>
                </div>
                @endif
              </div>
              
              @endif

              @if (Auth::user()->idPerm == 1 || Auth::user()->idPerm == 2 || Auth::user()->idPerm == 3)
              <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownListe" class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-yellow-700 md:p-0 md:w-auto">Listes<svg class="w-4 h-4 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg></button>
              <!-- Dropdown menu -->
              <div id="dropdownListe" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <ul class="py-2 text-sm" aria-labelledby="dropdownLargeButton">
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Élève par activité </a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Intervenant par activité </a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Mes élèves</a>
                  </li>
                </ul>
              </div>
              @endif
              @if (Auth::user()->idPerm == 4)
              <li>
                <a href="{{ route('mesActivite') }} " class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-yellow-700 md:p-0">Mes Activités</a>
              </li>
              @endif
              <li>
                <a href="{{ route('eleve') }}" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-yellow-700 md:p-0" aria-current="page">Liste d'activité</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="content mt-20">@yield('content')</div>
      @else
      <div class="content">@yield('content')</div>
      @endauth

    </div>

  </div>
</body>

</html>