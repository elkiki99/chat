<x-app-layout>
    <div
        class="flex flex-col items-center justify-center h-screen text-white bg-gradient-to-b from-white via-green-200 to-white">
        <div class="w-full max-w-4xl p-8 rounded-lg shadow-lg bg-opacity-30 backdrop-blur-lg">
            <div class="flex items-center justify-between mb-8">
                <x-application-logo class="w-auto h-16 text-green-800 animate-bounce" />
                <a wire:navigate href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-green-700 uppercase transition duration-150 ease-in-out bg-white border border-green-300 rounded-md shadow-sm dark:bg-green-800 dark:border-green-500 dark:text-green-300 hover:bg-green-50 dark:hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-green-800 disabled:opacity-25">
                    Login
                </a>
            </div>

            <div class="text-left">
                <h1 class="mb-4 text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-600 via-green-400 to-green-800 drop-shadow-lg">
                    Welcome to Chat App
                </h1>
                <p class="text-xl text-green-600 drop-shadow-sm">
                    Chat App is your gateway to staying connected with your loved ones, no matter where you are.
                    Share memories, have fun in group chats, and enjoy one-on-one conversations, all in one place!
                </p>
            </div>

            <div class="flex items-center justify-between mt-8">
                <div class="flex gap-6">
                    <p class="text-sm text-green-600">Version 1.0.0</p>
                    <p class="text-sm text-green-600">&copy; 2024 Chat App</p>
                </div>

                @guest
                    <a wire:navigate href="{{ route('register') }}" class="flex items-center gap-2">
                        <p class="text-green-600 text-xm">Get started</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="text-green-600 size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</x-app-layout>
