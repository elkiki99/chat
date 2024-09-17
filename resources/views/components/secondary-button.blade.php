<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-green-800 border border-green-300 dark:border-green-500 rounded-md font-semibold text-xs text-green-700 dark:text-green-300 uppercase tracking-widest shadow-sm hover:bg-green-50 dark:hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-green-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
