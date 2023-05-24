@extends('layouts.base') @section('title', "Gestion des Comptes") @section('content')
<div id="app">
    
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

        mounted() {},

        methods: {},
    }).mount('#app')
</script>
@endsection