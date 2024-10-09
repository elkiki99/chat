<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center px-4 py-2 bg-green-800 dark:bg-emerald-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-green-200 uppercase tracking-widest 
        hover:bg-green-700 dark:hover:bg-emerald-600 
        focus:bg-green-700 dark:focus:bg-emerald-600 
        active:bg-green-900 dark:active:bg-emerald-500 
        focus:outline-none 
        focus:ring-2 focus:ring-green-500 dark:focus:ring-emerald-400 
        focus:ring-offset-2 dark:focus:ring-offset-green-800 
        transition ease-in-out duration-150',
    ]) }}>
    {{ $slot }}
</button>
