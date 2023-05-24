@extends('layouts.base') @section('title', 'Mot de passe oublié') @section('content')
<div id="app" class="flex items-center h-screen justify-center ">
    <x-card>
        <h1 class="text-center text-3xl my-6">Mot de passe oublié</h1>
        
        @if (session('success'))
        <x-div-success-php>
            {{ session('success') }}
        </x-div-success-php>
        @endif
        
        @if (session('alert'))
        <x-div-error-php>{{ session('alert') }}</x-div-error-php>
        @endif

        <x-divErrorJs></x-divErrorJs>
        <form method="POST" action="{{ route('resetPassword') }}" @submit="checkForm">
            @csrf
            @error('email')
            <x-div-error-php>{{ $message }}</x-div-error-php>
            @enderror
            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Votre adresse mail : </label>
                <input name="email" type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
            </div>
            <div class="mb-6 flex flex-wrap justify-center">

                <x-button-primary class="w-9/12">
                    {{ __('Envoyer un lien') }}
                </x-button-primary>

            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('loginView') }}" class="text-sm font-medium text-yellow-500 hover:text-yellow-700 ">C'est bon j'ai retrouvé !</a>
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
        mounted() {},

        data() {
            return {
                errors: []
            }
        },
        methods: {
            checkForm: function(e) {
                this.errors = [];

                // Si une adresse mail est saisie
                if (!document.getElementById("email").value) {
                    this.errors.push('Rentrez une adresse mail');
                } else {
                    // Si il est conforme
                    if (!this.validEmail(document.getElementById("email").value)) {
                        this.errors.push('Rentrez une adresse mail correcte');
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