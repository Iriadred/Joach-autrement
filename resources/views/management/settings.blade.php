@extends('layouts.base') @section('title', "Paramètres") @section('content')
<div id="app">
    <!-- Activité -->

    <div class="flex justify-center">
        <div class="w-10/12 mb-6 py-4 text-3xl font-semibold">
            Paramètres
        </div>
    </div>
    <div class="flex justify-center">
        <div class="w-10/12 mb-2 overflow-hidden">
            @if (session('successActivite'))
            <x-div-success-php>{{ session('successActivite') }}</x-div-success-php>
            @endif

            @if (session('alertActivite'))
            <x-div-error-php>{{ session('alertActivite') }}</x-div-error-php>
            @endif
        </div>
    </div>



    
    <x-card-large>
        <div class="my-4">
            <p class="text-2xl font-semibold mb-2">Seconde vagues d'inscription</p>
            <div>
                <p class="mb-4"><span class="font-bold"> Etat actuel :</span> @if($etatWave == 1) 2ème vague en cours @elseif($etatWave == 0) 1ère vague en cours @endif</p>
                @if($etatWave == 1)
                <x-button-danger>{{ __("Annuler la deuxième vague") }}</x-button-danger>
                @elseif($etatWave == 0)
                <x-button-success>{{ __("Lancer la deuxième vague") }}</x-button-success>
                @endif
            </div>
        </div>
    </x-cars-large>

    <x-card-large>
        <div class="my-4">
            <p class="text-2xl font-semibold mb-2">Système de mail</p>
            <div>
                <p class="mb-4"><span class="font-bold"> Etat actuel :</span> @if($sysMailEtat == 1) Le système de mail est actif @elseif($sysMailEtat == 0) Le système de mail est inactif @endif</p>
                @if($sysMailEtat == 1)
                <x-button-danger>{{ __("Annuler la deuxième vague") }}</x-button-danger>
                @elseif($sysMailEtat == 0)
                <x-button-success>{{ __("Lancer la deuxième vague") }}</x-button-success>
                @endif
            </div>
        </div>
    </x-cars-large>


    <x-card-large>
        <div class="my-4">
            <p class="text-2xl font-semibold">Vider la base de donnée</p>
            <p class="mb-2"><span class="font-bold">⚠️ Attention ⚠️</span> Cette action supprime toutes les données de l'application c'est à utiliser à la fin de l'évenement</p>
            <div>
            <x-button-danger>{{ __("Remise à zéro") }}</x-button-danger>
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

            }
        },

        mounted() {

        },
        methods: {

        },
    }).mount('#app')
</script>
@endsection