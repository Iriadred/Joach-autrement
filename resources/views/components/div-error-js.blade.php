<div class="alert p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" v-if="errors.length">
<svg class="flex-shrink-0 inline w-5 h-5 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 28"><g data-name="Layer 2"><g data-name="alert-triangle"><rect width="24" height="24" transform="rotate(90 12 12)" opacity="0"/><path d="M22.56 16.3L14.89 3.58a3.43 3.43 0 0 0-5.78 0L1.44 16.3a3 3 0 0 0-.05 3A3.37 3.37 0 0 0 4.33 21h15.34a3.37 3.37 0 0 0 2.94-1.66 3 3 0 0 0-.05-3.04zm-1.7 2.05a1.31 1.31 0 0 1-1.19.65H4.33a1.31 1.31 0 0 1-1.19-.65 1 1 0 0 1 0-1l7.68-12.73a1.48 1.48 0 0 1 2.36 0l7.67 12.72a1 1 0 0 1 .01 1.01z"/><circle cx="12" cy="16" r="1"/><path d="M12 8a1 1 0 0 0-1 1v4a1 1 0 0 0 2 0V9a1 1 0 0 0-1-1z"/></g></g></svg>
    <b v-if="errors.length > 1">Veuillez corriger les erreurs suivantes :</b>
    <b v-if="errors.length == 1">Veuillez corriger l'erreur suivante :</b>
    <ul>
        <li v-for="error in errors">- ${ error }</li>
    </ul>
</div>  