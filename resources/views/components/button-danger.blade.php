<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-white bg-red-700 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center']) }}>
    {{ $slot }}
</button>