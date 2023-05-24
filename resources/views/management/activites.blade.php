@extends('layouts.base') @section('title', "Gestion des activités") @section('content')
<div id="app">
    <!-- Activité -->
    <x-card-large>

        <h1 class="mx-4 my-8 text-4xl text-gray-900 font-semibold">Activités</h1>

        @if (session('successActivite'))
        <x-div-success-php>{{ session('successActivite') }}</x-div-success-php>
        @endif

        @if (session('alertActivite'))
        <x-div-error-php>{{ session('alertActivite') }}</x-div-error-php>
        @endif
        <x-divErrorJs></x-divErrorJs>


        <div class="flex w-11/12 mb-4 justify-end">
            <x-button-danger onclick="window.location.href=`{{ route('allActivite') }}`" class="hidden" id="cancelBtn">{{ __("Annuler") }}</x-button-danger>
        </div>
        <form class="mx-4" method="POST" id="formActivite" @submit="checkFormIntervenant" action="{{ route('addActivite') }}">
            @csrf
            <div class="flex justify-around flex-wrap">
                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('titreActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="titreActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Titre de l'activité </label>
                    <input name="titreActivite" type="text" id="titreActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-2/12 w-10/12">

                    @error('typeActivité')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="typeActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Type de l'activité </label>
                    <select name="typeActivite" id="typeActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                        <option value="">Séléctionnez un type</option>
                        @foreach ($types as $type)
                        <option value="{{$type->codeType}}">{{$type->libType}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6 sm:w-2/12 w-10/12">

                    @error('categorieActivité')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="categorieActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Catégorie de l'activité </label>
                    <select name="categorieActivite" id="categorieActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                        <option value="">Séléctionnez une catégorie</option>
                        @foreach ($categories as $categorie)
                        <option value="{{$categorie->idCategorie}}">{{$categorie->libCategorie}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('enteteActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="enteteActivite" class="block mb-2 text-sm font-medium text-gray-900 ">En-tête de l'activité </label>
                    <textarea name="enteteActivite" id="enteteActivite" rows="2" class="resize-none block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500 " placeholder="Entrez une en-tête ici..."></textarea>
                </div>

                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('descriptionActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="descriptionActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Description de l'activité </label>
                    <textarea name="descriptionActivite" id="descriptionActivite" rows="2" class="resize-none block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500 " placeholder="Entrez une description ici..."></textarea>
                </div>


                <div class="sm:mb-6 sm:w-5/12 w-10/12 flex sm:flex-row flex-col justify-between">
                    @error('dateActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <div class="sm:w-3/12 w-12/12 mb-6 sm:mb-0">
                        @error('date')
                        <x-div-error-php>{{ $message }}</x-div-error-php>
                        @enderror
                        <label for="dateActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Date </label>
                        <input name="dateActivite" type="date" id="dateActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " min="07:00" max="20:00">
                    </div>
                    <div class="sm:w-3/12 w-12/12 mb-6 sm:mb-0">
                        @error('heureDebutActivite')
                        <x-div-error-php>{{ $message }}</x-div-error-php>
                        @enderror
                        <label for="heureDebutActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Heure de debut </label>
                        <input type="time" step="300" id="heureDebutActivite" name="heureDebutActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " min="07:00" max="20:00">
                    </div>
                    <div class="sm:w-3/12 w-12/12 mb-6 sm:mb-0">
                        @error('heureFinActivite')
                        <x-div-error-php>{{ $message }}</x-div-error-php>
                        @enderror
                        <label for="heureFinActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Heure de fin </label>
                        <input name="heureFinActivite" type="time" step="300" id="heureFinActivite" name="appt" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 " min="07:00" max="20:00">
                    </div>
                </div>

                <div class="mb-6 sm:w-2/12 w-10/12">
                    @error('lieuActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="lieuActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Lieu</label>
                    <input name="lieuActivite" type="text" id="lieuActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>
                <div class="mb-6 sm:w-2/12 w-10/12">
                    @error('nbPlacesActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="nbPlacesActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Nombre de places</label>
                    <input name="nbPlacesActivite" type="number" id="nbPlacesActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-5/12 w-10/12">
                    @error('intervenantActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="intervenant" class="block mb-2 text-sm font-medium text-gray-900 ">Intervenant</label>
                    <select name="intervenantActivite[]" multiple="" id="intervenant" class=" overflow-hidden hover:overflow-y-scroll bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                        @foreach ($intervenants as $intervenant)
                        <option value="{{$intervenant->idIntervenant}}">{{$intervenant->nomIntervenant}} {{$intervenant->prenomIntervenant}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6 sm:w-2/12 w-10/12">
                    @error('publicActivite')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <label for="publicActivite" class="block mb-2 text-sm font-medium text-gray-900 ">Public</label>
                    <input name="publicActivite" type="text" id="publicActivite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 ">
                </div>

                <div class="mb-6 sm:w-2/12 w-10/12">
                    @error('AMorPM')
                    <x-div-error-php>{{ $message }}</x-div-error-php>
                    @enderror
                    <p class="block mb-2 text-sm font-medium text-gray-900 ">Matin ou Après-midi ?</p>

                    <div class="mb-6 sm:w-5/12 w-10/12">
                        <div class="flex flex-row items-center my-5">
                            <div class="flex mr-2">
                                <input id="matin" type="radio" name="matinOuAprem" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500 " value="0">
                                <label for="matin" class="ml-2 text-sm font-medium text-gray-900">AM</label>
                            </div>
                            <div class="flex ml-2 ">
                                <input id="aprem" type="radio" name="matinOuAprem" class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 focus:ring-yellow-500 " value="1">
                                <label for="aprem" class="ml-2 text-sm font-medium text-gray-900">PM</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full flex justify-center mb-4">
                <x-button-primary class="sm:mr-12 sm:w-2/12 w-10/12">Enregister l'activité</x-button-primary>
            </div>
        </form>

        <div class="relative overflow-x-auto">
            @if(!empty($activites))
            <table class="w-full text-sm text-left text-gray-500">
                <tbody>
                    @foreach ($activites as $activite)
                    <tr class="bg-white border-b">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{$activite->codeType}}{{$activite->idActivite}} - {{$activite->titreActivite}}
                        </th>
                        <td class="px-6 py-4">

                            <x-button-info @click="editForm(`{{$activite->titreActivite}}`, `{{$activite->codeType}}`, `{{$activite->idCategorie}}`, `{{$activite->enteteActivite}}`, `{{$activite->descriptionActivite}}`, `{{$activite->lieu}}`, `{{$activite->nbPlaceActivite}}`, `{{$activite->publicActivite}}`, `{{$activite->dateActivite}}`, `{{$activite->heureDebutActivite}}`, `{{$activite->heureFinActivite}}`, `{{$activite->momentActivite}}`, `{{$activite->idActivite}}`)">
                                {{ __("Modifier") }}
                            </x-button-info>


                        </td>
                        <td class="px-6 py-4">
                            <x-button-danger onclick="window.location.href=`{{ route('deleteActivite', ['type' =>  $activite->codeType, 'id' => $activite->idActivite])}}`">{{ __("Supprimer") }}</x-button-danger>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <table class="w-full text-sm text-left text-gray-500">
                <tbody>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center">
                            <p>Aucune activité</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
        </x-card-large>

</div>
<script>
    const {
        createApp
    } = Vue

    createApp({
        delimiters: ['${', '}'],
        data() {
            return {

                isLoading: true,
                errors: [],
                anime: [],
                AnimeEditActivite: []



            }
        },

        mounted() {

            this.getAllAnime();



            @if(session('reNew'))

            /* Retrieve the data from the last request and display it in the form. */
            var element = document.getElementById("cancelBtn");
            element.classList.remove("hidden");
            var titre = document.getElementById("titreActivite");
            titre.value = "{{session('titreActivite')}}";
            var type = document.getElementById("typeActivite");
            type.value = "{{session('typeActivite')}}";
            var categorie = document.getElementById("categorieActivite");
            categorie.value = "{{session('categorieActivite')}}";
            var entete = document.getElementById("enteteActivite");
            entete.value = "{{session('enteteActivite')}}";
            var description = document.getElementById("descriptionActivite");
            description.value = "{{session('descriptionActivite')}}";
            var lieu = document.getElementById("lieuActivite");
            lieu.value = "{{session('lieuActivite')}}";
            var nbPlaces = document.getElementById("nbPlacesActivite");
            nbPlaces.value = "{{session('nbPlacesActivite')}}";
            var public = document.getElementById("publicActivite");
            public.value = "{{session('publicActivite')}}";

            var intervenant = document.getElementById("intervenant");
            /* Get a list of the session variable 'intervenantActivite' */
            var intervenantValue = "{{session('intervenantActivite')}}";

            /* Selecting the options in the select box. */
            for (var i = 0; i < intervenant.options.length; i++) {
                var option = intervenant.options[i];
                if (intervenantValue.indexOf(option.value) > -1) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            }
            @endif
        },
        methods: {
            /* Checking if the form is valid or not. */
            checkFormIntervenant: function(e) {
                this.errors = [];


                // Si un nom est saisie
                if (!document.getElementById("titreActivite").value) {
                    this.errors.push("Donnez un nom à l'activité");
                }

                /* Checking if the selected index of the dropdown is 0. */
                if (document.getElementById("typeActivite").selectedIndex == 0) {
                    this.errors.push("Donnez un type à l'activité");
                }

                /* Checking if the user has selected a category for the activity. */
                if (document.getElementById("categorieActivite      ").selectedIndex == 0) {
                    this.errors.push("Donnez une catégorie à l'activité");
                }

                // Si une en-tête est saisie
                if (!document.getElementById("enteteActivite").value) {
                    this.errors.push("Saisissez une en-tête pour l'activité");
                }

                // Si une description est saisie
                if (!document.getElementById("descriptionActivite").value) {
                    this.errors.push("Saisissez une description pour l'activité");
                }

                // Si une date est saisie
                if (!document.getElementById("dateActivite").value) {
                    this.errors.push("Saisissez une date pour l'activité");
                }

                // Si une heure de début est saisie
                if (!document.getElementById("heureDebutActivite").value) {
                    this.errors.push("Saisissez une heure de début pour l'activité");
                }

                // Si une heure de fin est saisie
                if (!document.getElementById("heureFinActivite").value) {
                    this.errors.push("Saisissez une heure de fin pour l'activité");
                }

                /* Checking if the value of the input field is empty. */
                if (!document.getElementById("lieuActivite").value) {
                    this.errors.push("Indiquez un lieu pour l'activité");
                }

                /* Checking if the value of the input field is empty or less than 1. */
                const nbPlaces = document.getElementById("nbPlacesActivite").value;
                if (!nbPlaces) {
                    this.errors.push("Indiquez le nombre de places disponibles pour l'activité");
                } else if (nbPlaces < 1) {
                    this.errors.push("Le nombre de places doit être supérieur ou égal à 1");
                }

                /* Checking if the dropdown is empty. */
                if (document.getElementById("intervenant").selectedIndex === -1) {
                    this.errors.push("Sélectionnez au moins un intervenant");
                }

                // Si une heure de début est saisie
                if (!document.getElementById("publicActivite").value) {
                    this.errors.push("Saisissez un public l'activité");
                }

                /* Checking if the checkbox is checked or not. */
                if (!document.getElementById("matin").checked && !document.getElementById("aprem").checked) {
                    this.errors.push("Sélectionnez une période pour l'activité : matin ou après-midi");
                }

                if (!this.errors.length) {
                    return true;
                }

                e.preventDefault();
            },
            timestampToDate: function(timestamp) {

                let date = new Date(timestamp * 1000);

                let year = date.getFullYear();
                let month = ('0' + (date.getMonth() + 1)).slice(-2);
                let day = ('0' + date.getDate()).slice(-2);
                let hours = ('0' + date.getHours()).slice(-2);
                let minutes = ('0' + date.getMinutes()).slice(-2);
                let seconds = ('0' + date.getSeconds()).slice(-2);
                const dateString = `${year}-${month}-${day}`;

                return dateString;
            },

            getAllAnime: function(e) {
                fetch(`{{$urlAPI}}/api/anime/getAll`)
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data)) {
                            this.anime = data;
                        } else {
                            this.anime = [];
                        }
                    });
                this.isLoading = false;
            },

            timestampToTime: function(timestamp) {

                let date = new Date(timestamp * 1000);


                let year = date.getFullYear();
                let month = ('0' + (date.getMonth() + 1)).slice(-2);
                let day = ('0' + date.getDate()).slice(-2);
                let hours = ('0' + date.getHours()).slice(-2);
                let minutes = ('0' + date.getMinutes()).slice(-2);
                let seconds = ('0' + date.getSeconds()).slice(-2);

                const timeString = `${hours}:${minutes}`;

                // Mettre à jour l'élément input time HTML avec l'heure convertie
                return timeString;

            },
            editForm: function(titre, type, categorie, entete, description, lieu, nbPlace, public, date, debut, fin, moment, id) {
                /* Retrieve the data from the last request and display it in the form. */
                this.AnimeEditActivite = [];
                if (this.anime.length > 0 && !this.isLoading) {
                    this.AnimeEditActivite = this.anime.filter(function(anime) {
                        return (anime.codeType == type) && (anime.idActivite == id);
                    });
                }
                var element = document.getElementById("cancelBtn");
                element.classList.remove("hidden");
                var titreInput = document.getElementById("titreActivite");
                titreInput.value = titre;
                var typeInput = document.getElementById("typeActivite");
                typeInput.value = type;
                var categorieInput = document.getElementById("categorieActivite");
                categorieInput.value = categorie;
                var enteteInput = document.getElementById("enteteActivite");
                enteteInput.value = entete;
                var descriptionInput = document.getElementById("descriptionActivite");
                descriptionInput.value = description;
                var lieuInput = document.getElementById("lieuActivite");
                lieuInput.value = lieu;
                var nbPlacesInput = document.getElementById("nbPlacesActivite");
                nbPlacesInput.value = nbPlace;
                var publicInput = document.getElementById("publicActivite");
                publicInput.value = public;

                var dateInput = document.getElementById("dateActivite");
                dateInput.value = this.timestampToDate(date);

                var debutInput = document.getElementById("heureDebutActivite");
                debutInput.value = this.timestampToTime(debut);

                var finInput = document.getElementById("heureFinActivite");
                finInput.value = this.timestampToTime(fin);

                if (moment == 0) {
                    var matin = document.getElementById("matin");
                    matin.checked = true;
                    var aprem = document.getElementById("aprem");
                    aprem.checked = false;

                } else {
                    var matin = document.getElementById("matin");
                    matin.checked = false;
                    var aprem = document.getElementById("aprem");
                    aprem.checked = true;
                }


                var intervenantInput = document.getElementById("intervenant");
                /* Get a list of the session variable 'intervenantActivite' */
                var intervenantValue = this.AnimeEditActivite;
                if (intervenantValue && intervenantValue.length > 0) {
                    for (var i = 0; i < intervenantInput.options.length; i++) {
                        var option = intervenantInput.options[i];
                        var optionValue = option.value;
                        var isSelected = false;
                        for (var j = 0; j < intervenantValue.length; j++) {
                            var interv = intervenantValue[j];
                            console.log(interv , optionValue);
                            if (interv.idIntervenant == optionValue) {
                                isSelected = true;
                                break;
                            }
                        }
                        option.selected = isSelected;
                    }
                }
                var typeActivite = document.getElementById("typeActivite")
                typeActivite.disabled = true;
                var formActivite = document.getElementById("formActivite");
                formActivite.action = "{{$urlEditAct}}" + `/${id}/${type}`;

            }



        },
    }).mount('#app')
</script>
@endsection