@extends('layouts.base') @section('title', 'Nouveau mot de passe') @section('content')
<div id="app" class="flex items-center h-screen justify-center ">
    <x-card>
        <h1 class="text-center text-3xl my-6">Nouveau mot de passe</h1>
        <x-divErrorJs></x-divErrorJs>


        <form method="POST" action="{{ route('setNewPassword') }}" @submit="checkForm">
            @csrf
            <input type="hidden" name="idUser" value="{{$idUser}}">
            <input type="hidden" name="token" value="{{$token}}">

            @error('password')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Nouveau mot de passe<span class="text-yellow-500">*</span> </label>
                <input name="password" type="password" id="password" class="@error('password') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmation mot de passe<span class="text-yellow-500">*</span> </label>
                <input name="password_confirmation" type="password" id="password_confirmation" class="@error('password_confirmation') is-invalid @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
            <div class="flex justify-center">
                <x-button-primary class="w-9/12">
                    {{ __('Modifier le mot de passe') }}
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
                errors:[]
            }
        },
        methods: {
            checkForm: function(e) {
                this.errors = [];
                var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/; //Le mot de passe doit contenir 8 à 16 caractères, au moins un chiffre, une lettre majuscule et une lettre minuscule.
                // var paswd= /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/; // Pour vérifier un mot de passe de 8 à 16 caractères contenant au moins un Maj, un Min, un chiffre et un caractère spécial.
                this.errors = [];


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
            }
        },
    }).mount('#app')
</script>

@endsection