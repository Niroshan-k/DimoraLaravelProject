<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center p-3 bg-brown-150 border border-transparent font-semibold text-xs text-white uppercase tracking-widest focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
