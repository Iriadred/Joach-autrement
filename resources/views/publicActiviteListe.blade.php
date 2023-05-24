@extends('layouts.base') @section('title', 'Listes des activités') @section('content')
<?php
    $idUsers = 1;
    $inscription = false;
?>
<div id="app">
    <div class="flex justify-center">
        <div class="w-10/12 mb-6 py-4 text-3xl font-semibold">
            Liste des activités
        </div>
    </div>
    <x-card-large>

        <div class="flex justify-around flex-row items-center flex-wrap ">
            <select class="bg-gray-50 border mb-4 sm:mb-0 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-9/12 sm:w-2/12 p-2.5" name="categorie" id="selectCat" ref="selectCat">
                <option value="">Choisir une Categorie</option>
                <option v-for="(categorie, index) in categories" :key="index" :value="categorie.idCategorie">${ categorie.libCategorie }</option>
            </select>
            <select class="bg-gray-50 border mb-4 sm:mb-0 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-9/12 sm:w-2/12 p-2.5" name="type" id="selectType" ref="selectType">
                <option value="">Choisir un type</option>
                <option v-for="(type, index) in types" :key="index" :value="type.codeType">${ type.libType }</option>
            </select>
            <input type="text" name="codeTexte" id="codeTexte" value="{{ old('codeTexte') }}" class="form-control mb-4 sm:mb-0 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-9/12 sm:w-2/12 p-2.5" placeholder="Code Activité">
            <label class="mr-4" for="CheckDispo">
                <input type="checkbox" id="CheckDispo" class="w-4 h-4 text-yellow-400 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500" name="disponible">
                Disponible
            </label>
            <button type="submit" v-on:click="filtre()" class="text-white bg-yellow-500 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm  sm:w-auto px-5 py-2.5 text-center">Filtrer</button>
        </div>
    </x-card-large>


    <div v-for="filtre in filtres" :key="filtre.id">
        <x-card-large>

            <p class="font-bold text-lg sm:text-xl">${ filtre.titreActivite }</p>
            <p class="italic font-semibold">${filtre.enteteActivite}</p>
            <p class="mb-4">${filtre.descriptionActivite}</p>
            <div class="flex flex-wrap ">
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Public :</span> ${filtre.publicActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Places restantes :</span> ${filtre.placeRestante }</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Lieux :</span> ${filtre.lieu}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Code activite :</span> ${filtre.codeType}${filtre.idActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Date :</span> ${filtre.dateActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Horaire :</span> ${filtre.heureDebutActivite}-${filtre.heureFinActivite}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Type :</span> ${filtre.type.libType}</p>
                <p class="w-6/12 sm:w-1/5"><span class="font-bold">Catégorie :</span> ${filtre.categorie.libCategorie}</p>
            </div>
        </x-card-large>
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
            fetch('{{$urlAPI}}/api/activite')
                .then(response => response.json())
                .then(data => {
                    this.items = data;
                    this.test();
                    this.filtres = this.items;

                    //console.log(data);
                });
            /* Il récupère les données de l'API et les place dans la variable inscrit. */
            this.setCat();
            this.setType();
        },

        data() {
            return {
                items: [],
                filtres: [],
                inscrits: [],
                categories: [],
                timestamp: Date.now(),
                types: [],
                isInscrit: false
            }
        },
        methods: {
            functionIsInscrit: function(filtre) {
                this.isInscrit = false;
                const index = this.inscrits.findIndex(inscrit => {
                    return inscrit.codeType === filtre.codeType && inscrit.idActivite === filtre.idActivite;
                });

                if (index !== -1) {
                    this.isInscrit = true;
                }

                return this.isInscrit;
            },

            setCat() {
                fetch('{{$urlAPI}}/api/categorie')
                    .then(response => response.json())
                    .then(data => {
                        this.categories = data;
                    });

            },
            allerInscription: function(codeType, idActivite) {
                window.location.href = `Inscription/{{Auth::user()->idUsers}}/${codeType}/${idActivite}`;
            },
            allerDesinscription: function(codeType, idActivite) {
                window.location.href = `Desinscription/{{Auth::user()->idUsers}}/${codeType}/${idActivite}`;
            },
            setType() {
                fetch('{{$urlAPI}}/api/type')
                    .then(response => response.json())
                    .then(data => {
                        this.types = data;
                    });

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
                    let date = new Date(item.heureFinActivite * 1000);

                    let year = date.getFullYear();
                    let month = ('0' + (date.getMonth() + 1)).slice(-2);
                    let day = ('0' + date.getDate()).slice(-2);
                    let hours = ('0' + date.getHours()).slice(-2);
                    let minutes = ('0' + date.getMinutes()).slice(-2);
                    let seconds = ('0' + date.getSeconds()).slice(-2);

                    let newHeureFin = `${hours}:${minutes}`;



                    item.heureFinActivite = newHeureFin;


                });

                this.items.forEach(item => {
                    let date = new Date(item.heureDebutActivite * 1000);

                    let year = date.getFullYear();
                    let month = ('0' + (date.getMonth() + 1)).slice(-2);
                    let day = ('0' + date.getDate()).slice(-2);
                    let hours = ('0' + date.getHours()).slice(-2);
                    let minutes = ('0' + date.getMinutes()).slice(-2);
                    let seconds = ('0' + date.getSeconds()).slice(-2);

                    let newHeureDebut = `${hours}:${minutes}`;



                    item.heureDebutActivite = newHeureDebut;

                });

                let currentTimestamp = Math.floor(Date.now() / 1000);

                this.items.sort(function(a, b) {
                    return b.dateStamp - a.dateStamp;
                });

                this.items = this.items.filter(function(item) {
                    return item.dateStamp >= currentTimestamp;
                });
                //console.log(this.items);
            },
            filtre() {
                this.filtres = [];
                this.filtres = this.items;

                const selectCat = document.getElementById('selectCat').value;
                const selectType = document.getElementById('selectType').value;
                const codeTexte = document.getElementById('codeTexte').value;
                const CheckDispo = document.getElementById('CheckDispo').checked;

                //console.log(CheckDispo);

                if (selectCat !== '') {
                    this.filtres = this.filtres.filter(function(activite) {
                        return activite.idCategorie === parseInt(selectCat);
                    });
                }
                if (selectType !== '') {
                    this.filtres = this.filtres.filter(function(activite) {
                        return activite.codeType === selectType;
                    });
                }
                if (codeTexte !== '') {
                    this.filtres = this.filtres.filter(function(activite) {
                        // Utilisation de la méthode `match()` pour filtrer la liste en fonction de la valeur de `codeTexte`
                        return activite.codeActivite.match(new RegExp(codeTexte, "i"));
                    });
                }
                if (CheckDispo === true) {
                    this.filtres = this.filtres.filter(function(activite) {
                        return parseInt(activite.placeRestante) > 0 && activite.activiteAnnule == false;
                    });
                }
            }
        }

    }).mount('#app')
</script>
@endsection