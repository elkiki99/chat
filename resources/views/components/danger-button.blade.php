<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-red-800 border border-red-300 dark:border-red-500 rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest shadow-sm hover:bg-red-50 dark:hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-red-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
