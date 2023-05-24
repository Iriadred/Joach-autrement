@extends('layouts.base') @section('title', "Gestion des catégories") @section('content')
<div id="app">
    <x-card-large>
        <h1 class="mx-4 my-8 text-4xl text-gray-900 font-semibold">Catégories</h1>
        <x-div-error-js></x-div-error-js>

        @if (session('successCategorie'))
        <x-div-success-php>{{ session('successCategorie') }}</x-div-success-php>
        @endif

        @if (session('alertCategorie'))
        <x-div-error-php>{{ session('alertCategorie') }}</x-div-error-php>
        @endif

        <form class="mx-4" method="POST" @submit="checkForm" action="{{ route('addCategorie') }}">
            @csrf
            @error('libelleCategorie')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="libelleCat" class="block mb-2 text-sm font-medium text-gray-900 ">Ajouter une Catégorie : </label>
                <input name="libelleCategorie" type="text" id="libelleCate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5  ">
            </div>
        </form>
        <div class="relative overflow-x-auto">




            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <tbody>
                    @isset($categories)
                    @foreach ($categories as $categorie)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$categorie->libCategorie}}
                        </th>
                        <td class="px-6 py-4">

                            <x-button-info @click="editCate({{ $categorie->idCategorie }}, `{{ $categorie->libCategorie }}`)" data-modal-target="CategorieModal" data-modal-toggle="CategorieModal">
                                {{ __("Modifier") }}
                            </x-button-info>


                        </td>
                        <td class="px-6 py-4">
                            <x-button-danger onclick="window.location.href=`{{ route('deleteCategorie', ['page' => 'management', 'id' => $categorie->idCategorie])}}`">{{ __("Supprimer") }}</x-button-danger>
                        </td>
                    </tr>
                    @endforeach
                    
                    @else
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center">
                            <p>Aucune Catégorie</p>
                        </td>
                    </tr>
                    @endisset
                </tbody>
            </table>

        </div>

        <!-- modal -->
        <div id="CategorieModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full bg-[#00000090]">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center " data-modal-hide="CategorieModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Annuler</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 ">Modifier la catégorie</h3>
                        <form class="space-y-6" id="formEditCategorie" action="" method="post">
                            <div class="mb-6">
                                @csrf
                                <label for="libelleEditCate" class="block mb-2 text-sm font-medium text-gray-900 ">Nom de la catégorie : </label>
                                <input name="libelle" type="text" id="libelleEditCate" value="" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                            </div>
                            <div class="mb-6 flex flex-wrap justify-center">
                                <x-button-info class="w-9/12">
                                    {{ __("Modifier") }}
                                </x-button-info>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </x-cars-large>
</div>
<script>
    const {
        createApp
    } = Vue

    createApp({
        delimiters: ['${', '}'],

        data() {
            return {
                errors: []
            }
        },
        methods: {
            checkForm: function(e) {
                this.errors = [];


                // Si un password est rentré
                if (!document.getElementById("libelleCate").value) {
                    this.errors.push('Rentrez un nom de catégorie');
                }

                if (!this.errors.length) {
                    return true;
                }

                e.preventDefault();
            },
            editCate: function(id, nom) {
                var libelleEditCate = document.getElementById("libelleEditCate");
                libelleEditCate.value = nom;
                var formEditCategorie = document.getElementById("formEditCategorie");
                formEditCategorie.action = "{{$urlEditCate}}" + `/${id}`;

            }

        },
    }).mount('#app')
</script>
@endsection