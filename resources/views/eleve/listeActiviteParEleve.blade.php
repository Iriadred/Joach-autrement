@extends('layouts.base') @section('title', 'Mes activités') @section('content')
<?php
$idUsers = 1;
$inscription = false;
?>
<div id="app">
    <div class="flex justify-center">
        <div class="w-10/12 mb-6 py-4 text-3xl font-semibold">
            Mes activités
        </div>
    </div>

    <x-card-large>
        <p><span class="font-bold">Temps total d'activités : </span>${tempsTotal}</p>
    </x-card-large>

    <div class="flex justify-center">
        <div class="w-10/12 mb-2 overflow-hidden">
            @if (session('successActivite'))
            <x-div-success-php>{{ session('successActivite') }}</x-div-success-php>
            @endif
        </div>
    </div>

    <div class="flex justify-center">
        <div class="w-10/12 mb-2 overflow-hidden">
            @if (session('alertActivite'))
            <x-div-error-php class="">{{ session('alertActivite') }}</x-div-error-php>
            @endif
        </div>
    </div>

    <div v-for="item in items" :key="item.id">
        <!-- 
        <div v-for="inscrit in inscrits" :key="inscrit.id">

            <div v-if="inscrit.idUsers == {{$idUsers}} && inscrit.codeType == item.codeType && inscrit.idActivite == item.idActivite">
            </div>

        </div> -->
        <!-- /* A function that checks if the user is already registered for the activity. If he is, the button will
            be red and say "Annulé". If he is not, the button will be green and say "rejoindre".  -->
        <x-card-large>
            <p class="font-bold text-lg sm:text-xl">${ item.titreActivite }</p>
            <p class="italic font-semibold">${item.enteteActivite}</p>
            <p class="mb-4">${item.descriptionActivite}</p>
            <div class="flex flex-wrap">
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Public :</span> ${item.publicActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Lieux :</span> ${item.lieu }</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Code activite :</span> ${item.codeType}${item.idActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Date :</span> ${item.dateActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Horaire :</span> ${item.heureDebutActivite}-${item.heureFinActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Type :</span> ${item.type.libType}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Catégorie :</span> ${item.categorie.libCategorie} </p>
            </div>
            <div class="w-full mt-6 flex items-end">
                <x-button-danger v-if="item.activiteAnnule == false" @click="allerDesinscription(item.codeType, item.idActivite)">Desinscription</x-button-success>
                <x-button-cancel v-if="item.activiteAnnule == true" disabled>Activité annulée ❌</x-button-cancel>
            </div>
        </x-card-large>
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
        mounted() {
            /* Il récupère les données de l'API et les place ensuite dans la variable items */
            fetch('{{$urlAPI}}/api/insc/{{Auth::user()->idUsers}}')
                .then(response => response.json())
                .then(data => {
                    this.items = data;
                    this.test();


                    //console.log(data);
                });
            /* Il récupère les données de l'API et les place dans la variable inscrit. */
            fetch('{{$urlAPI}}/api/inscrit/{{Auth::user()->idUsers}}')
                .then(response => response.json())
                .then(data => {
                    this.inscrits = data;
                    //console.log(data);
                });
        },

        data() {
            return {
                items: [],
                tempsTotal: "",
                total: 0,
                inscrits: [],
                isInscrit: false,
            }
        },
        methods: {


            allerDesinscription: function(codeType, idActivite) {
                window.location.href = `DesinscriptionParEleve/{{Auth::user()->idUsers}}/${codeType}/${idActivite}`;
            },

            CalculTime() {

                var hours = Math.floor(this.total / 3600);
                var minutes = Math.floor((this.total % 3600) / 60);

                this.tempsTotal = hours + ":" + minutes.toString().padStart(2, '0');
                console.log(this.tempsTotal)

            },


            test() {

                this.items.forEach(item => {
                    let date = new Date(item.dateActivite * 1000);

                    let year = date.getFullYear();
                    let month = ('0' + (date.getMonth() + 1)).slice(-2);
                    let day = ('0' + date.getDate()).slice(-2);
                    let hours = ('0' + date.getHours()).slice(-2);
                    let minutes = ('0' + date.getMinutes()).slice(-2);
                    let seconds = ('0' + date.getSeconds()).slice(-2);

                    let newDateActivite = `${day}/${month}`;



                    item.dateActivite = newDateActivite;


                });
                this.items.forEach(item => {
                    let dateF = new Date(item.heureFinActivite * 1000);

                    let yearF = dateF.getFullYear();
                    let monthF = ('0' + (dateF.getMonth() + 1)).slice(-2);
                    let dayF = ('0' + dateF.getDate()).slice(-2);
                    let hoursF = ('0' + dateF.getHours()).slice(-2);
                    let minutesF = ('0' + dateF.getMinutes()).slice(-2);
                    let secondsF = ('0' + dateF.getSeconds()).slice(-2);

                    let dateD = new Date(item.heureDebutActivite * 1000);

                    let yearD = dateD.getFullYear();
                    let monthD = ('0' + (dateD.getMonth() + 1)).slice(-2);
                    let dayD = ('0' + dateD.getDate()).slice(-2);
                    let hoursD = ('0' + dateD.getHours()).slice(-2);
                    let minutesD = ('0' + dateD.getMinutes()).slice(-2);
                    let secondsD = ('0' + dateD.getSeconds()).slice(-2);
                    if (item.activiteAnnule == false) {
                        this.total = this.total + (item.heureFinActivite - item.heureDebutActivite);
                    }

                    let newHeureDebut = `${hoursD}:${minutesD}`;

                    let newHeureFin = `${hoursF}:${minutesF}`;

                    item.heureFinActivite = newHeureFin;
                    item.heureDebutActivite = newHeureDebut;


                });
                this.CalculTime();
            },

        }
    }).mount('#app')
</script>
@endsection