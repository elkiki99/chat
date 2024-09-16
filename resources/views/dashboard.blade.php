<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar: 1/3 of the width -->
        <div class="flex flex-col w-1/4 p-4 text-white bg-gray-100">
            <h2 class="mb-4 text-lg font-bold">Chats</h2>
            @forelse(App\Models\User::all() as $chat)
                <ul>
                    <li><a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Chat 1</a></li>
                </ul>
            @empty
                <p>No chats found.</p>
            @endforelse
        </div>

        <!-- Main Content: 2/3 of the width -->
        <div class="flex flex-col w-3/4 p-10 bg-gray-200">
            <div class="flex-1 overflow-auto">
                <!-- Chat messages go here -->
                <div class="mb-4">
                    <p class="p-2 bg-white rounded shadow">Hello! How are you?</p>
                </div>
                <div class="mb-4">
                    <p class="p-2 bg-green-100 rounded shadow">I'm good, thanks! How about you?</p>
                </div>
                <!-- More chat messages -->
            </div>

            <!-- Message input area -->
            <div class="flex items-center pt-2 mt-4border-gray-300">
                <button class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                </button>

                <input type="text" placeholder="Type a message..."
                    class="flex-1 p-2 mr-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                <button
                    class="p-2 text-white bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Send
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
