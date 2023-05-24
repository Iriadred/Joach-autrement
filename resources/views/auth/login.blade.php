@extends('layouts.base') @section('title', 'Connexion') @section('content')
<div id="app" class="flex items-center h-screen justify-center ">
    <x-card>
        <h1 class="text-center text-3xl my-6">Connexion</h1>

        @if (session('success'))
        <x-div-success-php>{{ session('success') }}</x-div-success-php>
        @endif

        @if (session('alert'))
        <x-div-error-php>{{ session('alert') }}</x-div-error-php>
        @endif

        <x-divErrorJs></x-divErrorJs>

        <form method="POST" action="{{route('loginUser')}}" @submit="checkForm">
            @csrf
            @error('class')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="selectClasse" class="block mb-2 text-sm font-medium text-gray-900">Votre classe </label>
                <select @change='updateName' name="class" id="selectClasse" ref="selectClasse" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="">
                    <option value="">Choisir une classe</option>
                    <option value="personnel">Le personnel</option>
                    <option v-for="(classe, index) in classes" :key="index" :value="classe.idClasse">${ classe.nomClasse }</option>
                </select>
            </div>
            @error('name')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="selectName" class="block mb-2 text-sm font-medium text-gray-900">Votre nom </label>
                <select name="name" id="selectName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" placeholder="">
                    <option value="">Selectionner votre nom</option>
                    <option v-for="(name, index) in names" :key="index" :value="name.idUsers" :disabled="name.validationMail == 0">${ name.nomUsers }</option>
                </select>
            </div>

            @error('password')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Votre mot de passe </label>
                <input name="password" type="password" id="password" autocomplete="current-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
            <div class="mb-6 flex justify-center">
                <x-button-primary class="w-9/12">
                    {{ __('Connexion') }}
                </x-button-primary>
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('lostPassword') }}" class="text-sm font-medium text-yellow-500 hover:text-yellow-700">Mot de passe oublié ?</a>
                <a href="{{ route('registerView') }}" class="text-sm font-medium text-yellow-500 hover:text-yellow-700">Je n'ai pas de compte</a>
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
                errors: []
            }
        },
        methods: {
            setClasse() {
                fetch("{{ route('GetClasse') }}")
                    .then(response => response.json())
                    .then(data => {
                        this.classes = data;
                    });
            },
            updateName() {
                const idClasse = this.$refs.selectClasse.value;
                if (document.getElementById("selectClasse").selectedIndex != 0) {
                    fetch(`{{$urlAPI}}/api/eleves/getRegisterByClass/${idClasse}`)
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

                // Si un nom ou une classe est sélectionné 
                if (document.getElementById("selectName").selectedIndex == 0 || document.getElementById("selectClasse").selectedIndex == 0) {
                    this.errors.push('Qui êtes-vous ?');
                }

                // Si un password est rentré
                if (!document.getElementById("password").value) {
                    this.errors.push('Rentrez un mot de passe');
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