@extends('layouts.base') @section('title', "Gestion des intervenants") @section('content')
<div id="app">
    <!-- Intervenant -->
    <x-card-large>
    
        <h1 class="mx-4 my-8 text-4xl text-gray-900 font-semibold">Intervenants</h1>

        @if (session('successIntervenant'))
        <x-div-success-php>{{ session('successIntervenant') }}</x-div-success-php>
        @endif

        @if (session('alertIntervenant'))
        <x-div-error-php>{{ session('alertIntervenant') }}</x-div-error-php>
        @endif
        <x-divErrorJs></x-divErrorJs>
        <form class="mx-4" method="POST" @submit="checkFormIntervenant" action="{{ route('addIntervenant') }}">
            @csrf
            <div class="flex justify-around flex-wrap">
                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('nomIntervenant')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="nomIntervenant" class="block mb-2 text-sm font-medium text-gray-900 ">Nom de l'intervenant: </label>
                    <input name="nomIntervenant" type="text" id="nomIntervenant" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('prenomIntervenant')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="prenomIntervenant" class="block mb-2 text-sm font-medium text-gray-900 ">Prénom de l'intervenant : </label>
                    <input name="prenomIntervenant" type="text" id="prenomIntervenant" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('professionIntervenant')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="professionIntervenant" class="block mb-2 text-sm font-medium text-gray-900 ">Profession de l'intervenant :</label>
                    <input name="professionIntervenant" type="text" id="professionIntervenant" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('intExt')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <p class="block mb-2 text-sm font-medium text-gray-900 ">Interne ou externe ?</p>

                    <div class="flex flex-row items-center my-5">
                        <div class="flex mr-2">
                            <input checked id="isExt" type="radio" name="intExt" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500  focus:ring-2 " value="1">
                            <label for="isExt" class="ml-2 text-sm font-medium text-gray-900 ">Externe</label>
                        </div>
                        <div class="flex ml-2 ">
                            <input id="isInt" type="radio" name="intExt" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500  focus:ring-2 " value="0">
                            <label for="isInt" class="ml-2 text-sm font-medium text-gray-900 ">Interne</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full flex justify-center mb-4">
                <x-button-primary class="sm:mr-12 sm:w-2/12 w-10/12">Ajouter un intervenant</x-button-primary>
            </div>
        </form>
        <div class="relative overflow-x-auto">
            @if(!empty($intervenants))
            <table class="w-full text-sm text-left text-gray-500 ">
                <tbody>
                    @foreach ($intervenants as $intervenant)
                    <tr class="bg-white border-b ">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{$intervenant->nomIntervenant}} {{$intervenant->prenomIntervenant}}
                        </th>
                        <td class="px-6 py-4">

                            <x-button-info @click="editIntervenant({{$intervenant->idIntervenant}}, `{{$intervenant->nomIntervenant}}`, `{{$intervenant->prenomIntervenant}}`, `{{$intervenant->professionIntervenant}}`, {{$intervenant->isExt}})" data-modal-target="ModalEditIntervenant" data-modal-toggle="ModalEditIntervenant">
                                {{ __("Modifier") }}
                            </x-button-info>


                        </td>
                        <td class="px-6 py-4">
                            <x-button-danger onclick="window.location.href=`{{ route('deleteIntervenant', ['id' => $intervenant->idIntervenant])}}`">{{ __("Supprimer") }}</x-button-danger>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <table class="w-full text-sm text-left text-gray-500 ">
                <tbody>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center">
                            <p>Aucun intervenant</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
        </x-cars-large>
        <!-- modal -->
        <div id="ModalEditIntervenant" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full bg-[#00000090]">
            <div class="relative w-full h-full max-w-lg md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="ModalEditIntervenant">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Annuler</span>
                    </button>
                    <div class="px-6 py-6 lg:px-8">
                        <h3 class="mb-4 text-xl font-medium text-gray-900 ">Modifier</h3>
                        <form class="space-y-6" id="formEditIntervenant" action="#" method="post">
                            <div class="mb-6">
                                @csrf
                                <div class="flex justify-around flex-wrap">
                                    <div class="mb-6 sm:w-5/12 w-10/12">

                                        <label for="nomIntervenantEdit" class="block mb-2 text-sm font-medium text-gray-900 ">Nom de l'intervenant: </label>
                                        <input name="nomIntervenantEdit" type="text" id="nomIntervenantEdit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                                    </div>

                                    <div class="mb-6 sm:w-5/12 w-10/12">

                                        <label for="prenomIntervenantEdit" class="block mb-2 text-sm font-medium text-gray-900 ">Prénom de l'intervenant : </label>
                                        <input name="prenomIntervenantEdit" type="text" id="prenomIntervenantEdit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                                    </div>

                                    <div class="mb-6 sm:w-5/12 w-10/12">

                                        <label for="professionIntervenantEdit" class="block mb-2 text-sm font-medium text-gray-900 ">Profession de l'intervenant :</label>
                                        <input name="professionIntervenantEdit" type="text" id="prefessionIntervenantEdit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                                    </div>

                                    <div class="mb-6 sm:w-5/12 w-10/12">

                                        <p class="block mb-2 text-sm font-medium text-gray-900 ">Interne ou externe ?</p>

                                        <div class="flex flex-row items-center my-5">
                                            <div class="flex mr-2">
                                                <input id="isExtEdit" type="radio" name="intExtEdit" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500  focus:ring-2 " value="1">
                                                <label for="isExtEdit" class="ml-2 text-sm font-medium text-gray-900 ">Externe</label>
                                            </div>
                                            <div class="flex ml-2 ">
                                                <input id="isIntEdit" type="radio" name="intExtEdit" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500  focus:ring-2 " value="0">
                                                <label for="isIntEdit" class="ml-2 text-sm font-medium text-gray-900 ">Interne</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            editIntervenant: function(id, nom, prenom, profession, isExt) {
                var libelleEdit = document.getElementById("nomIntervenantEdit");
                libelleEdit.value = nom;
                var libelleEdit = document.getElementById("prenomIntervenantEdit");
                libelleEdit.value = prenom;
                var libelleEdit = document.getElementById("prefessionIntervenantEdit");
                libelleEdit.value = profession;
                if (isExt == 1) {
                    var libelleEdit = document.getElementById("isExtEdit");
                    libelleEdit.checked = true;
                } else {
                    var libelleEdit = document.getElementById("isIntEdit");
                    libelleEdit.checked = true;
                }
                var formEditCategorie = document.getElementById("formEditIntervenant");
                formEditCategorie.action = "{{$urlIntervenant}}" + `/${id}`;
            },
            checkFormIntervenant: function(e) {
                this.errors = [];

                // Si un nom est saisie
                if (!document.getElementById("nomIntervenant").value) {
                    this.errors.push("Donnez un nom à l'intervenant");
                }

                // Si un prenom est rentré
                if (!document.getElementById("prenomIntervenant").value) {
                    this.errors.push("Donnez un prenom à l'intervenant");
                }

                // Si une profession est saisie
                if (!document.getElementById("professionIntervenant").value) {
                    this.errors.push("Donnez la profession de l'intervenant");
                }

                // Si un etat est rentré
                if (!document.getElementById("isInt").checked && !document.getElementById("isExt").checked) {
                    this.errors.push('Cet intervenant est interne au lycée ou externe au lycée ?');
                }

                if (!this.errors.length) {
                    return true;
                }

                e.preventDefault();
            }

        },
    }).mount('#app')
</script>
@endsection