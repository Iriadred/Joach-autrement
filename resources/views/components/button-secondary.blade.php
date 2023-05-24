<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center']) }}>
    {{ $slot }}
</button>