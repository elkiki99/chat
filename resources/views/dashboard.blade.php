<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar: 1/3 of the width -->
        <x-sidebar />

        <!-- Main Content: 2/3 of the width -->
        <div class="flex flex-col w-3/4 bg-gray-200">
            <div class="flex-1 p-10 overflow-auto">
                <!-- Chat -->
                <div class="mb-4">
                    <p class="p-2 bg-white rounded shadow">Hello! How are you?</p>
                </div>
                <div class="mb-4">
                    <p class="p-2 bg-green-100 rounded shadow">I'm good, thanks! How about you?</p>
                </div>
            </div>

            <!-- Message input -->
            <div class="flex items-center px-4 py-2 bg-gray-300 mt-4border-gray-300">
                <button class="">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                </button>

                <x-text-input type="text" placeholder="Type a message here..." class="w-full mx-2" />
                <button
                    class="text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                      </svg>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
