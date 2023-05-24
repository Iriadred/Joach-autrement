@extends('layouts.base') @section('title', 'Inscription') @section('content')
<div id="app" class="flex items-center h-screen justify-center ">
    <x-card>
        <h1 class="text-center text-3xl my-6">Inscription</h1>
        <x-divErrorJs></x-divErrorJs>

        <form method="POST" action="{{ route('VerfiRegister') }}" @submit="checkForm">
            @csrf
            @error('class')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="selectClasse" class="block mb-2 text-sm font-medium text-gray-900">Classe<span class="text-yellow-500">*</span> </label>
                <select @change='updateName' name="class" id="selectClasse" ref="selectClasse" class="@error('class') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="">
                    <option value="">Choisir une classe</option>
                    <option v-for="(classe, index) in classes" :key="index" :value="classe.idClasse">${ classe.nomClasse }</option>
                </select>
            </div>
            @error('name')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="selectName" class="block mb-2 text-sm font-medium text-gray-900">Nom<span class="text-yellow-500">*</span> </label>
                <select name="name" id="selectName" class="@error('name') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="">
                    <option value="">Selectionner votre nom</option>
                    <option v-for="(name, index) in names" :key="index" :value="name.idUsers">${name.nomUsers}</option>
                </select>
            </div>

            @error('email')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Adresse mail<span class="text-yellow-500">*</span> </label>
                <input name="email" autocomplete="email" type="email" id="email" class="@error('email') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="">
            </div>

            @error('password')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe<span class="text-yellow-500">*</span> </label>
                <input name="password" autocomplete="new-password" type="password" id="password" class="@error('password') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmation mot de passe<span class="text-yellow-500">*</span> </label>
                <input name="password_confirmation" autocomplete="new-password" type="password" id="password_confirmation" class="@error('password_confirmation') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('loginView') }}" class="text-sm font-medium text-yellow-500 hover:text-yellow-700">J'ai déjà un compte</a>
                <x-button-primary>
                    {{ __('S\'inscrire') }}
                </x-button-primary>
            </div>
        </form>
    </x-card>
</div>


<script>
    const {
        createApp
    } = Vue

    createApp({
        delimiters: ['${', '}'],
        mounted() {
            this.setClasse();
        },

        data() {
            return {
                classes: [],
                names: [],
                errors: [],

            }
        },
        methods: {
            setClasse() {
                fetch(`{{ route('GetClasse') }}`)
                    .then(response => response.json())
                    .then(data => {
                        this.classes = data;
                    });
            },
            updateName() {
                const idClasse = this.$refs.selectClasse.value;
                if (document.getElementById("selectClasse").selectedIndex != 0) {
                    fetch(`{{$urlAPI}}/api/eleves/getUnregisterByClass/${idClasse}`)
                        .then(response => response.json())
                        .then(data => {
                            this.names = data;
                        });
                } else {
                    this.names = null;
                }
                document.getElementById("selectName").selectedIndex = 0;
            },
            checkForm: function(e) {
                this.errors = [];
                var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/; //Le mot de passe doit contenir 8 à 16 caractères, au moins un chiffre, une lettre majuscule et une lettre minuscule.
                // var paswd= /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/; // Pour vérifier un mot de passe de 8 à 16 caractères contenant au moins un Maj, un Min, un chiffre et un caractère spécial.

                // Si un nom ou une classe est sélectionné 
                if (document.getElementById("selectName").selectedIndex == 0 || document.getElementById("selectClasse").selectedIndex == 0) {
                    this.errors.push('Qui êtes-vous ?');
                }
                // Si une adresse mail est saisie
                if (!document.getElementById("email").value) {
                    this.errors.push('Rentrez une adresse mail');
                } else {
                    // Si il est conforme
                    if (!this.validEmail(document.getElementById("email").value)) {
                        this.errors.push('Rentrez une adresse mail correcte');
                    }
                }

                // Si un password est rentré
                if (!document.getElementById("password").value) {
                    this.errors.push('Rentrez un mot de passe');
                } else {
                    // Si il n'est pas identique à la confirmation
                    if (document.getElementById("password_confirmation").value !== document.getElementById("password").value) {
                        this.errors.push('Les deux mots de passe ne sont pas identiques');
                    }
                    if (!document.getElementById("password").value.match(passw)) {
                        this.errors.push("Le mot de passe doit contenir 8 à 16 caractères, au moins un chiffre, un symbole, une lettre majuscule et une lettre minuscule.");
                    }
                }

                if (!this.errors.length) {
                    return true;
                }

                e.preventDefault();
            },
            validEmail: function(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        },
    }).mount('#app')
</script>

@endsection