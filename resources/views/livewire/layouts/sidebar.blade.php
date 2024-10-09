<!-- Sidebar -->
<aside class="fixed bottom-0 left-0 right-0 z-50 flex justify-around w-full h-12 p-1 text-white bg-gray-100 border-t border-gray-300 sm:border-r sm:border-t-0 sm:justify-start sm:flex-col sm:h-full sm:w-12">
    <!-- Chats -->
    <div class="flex items-center justify-center p-2 my-1 rounded-lg hover:cursor-pointer hover:bg-gray-150 {{ $activeComponent === 'chats' ? 'bg-gray-200' : '' }}">
        <a wire:click="selectChats">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                class="text-gray-800 size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
            </svg>
        </a>
    </div>

    <!-- Contacts -->
    <div class="flex items-center justify-center p-2 my-1 rounded-lg hover:cursor-pointer hover:bg-gray-150 {{ $activeComponent === 'contacts' ? 'bg-gray-200' : '' }}">
        <a wire:click="selectContacts">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                stroke="currentColor" class="text-gray-800 size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
        </a>
    </div>

    <!-- Archived -->
    <div class="flex items-center justify-center p-2 my-1 rounded-lg hover:cursor-pointer hover:bg-gray-150 {{ $activeComponent === 'archived' ? 'bg-gray-200' : '' }}">
        <a wire:click="selectArchived">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                stroke="currentColor" class="text-gray-800 size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
        </a>
    </div>

    <!-- Logout -->
    <div class="flex items-center justify-center p-2 my-1 rounded-lg hover:cursor-pointer hover:bg-gray-150">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="text-gray-800 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 4.5-15 15m0 0h11.25m-11.25 0V8.25" />
                </svg>
            </button>
        </form>
    </div>

    <!-- Profile -->
    <a wire:navigate href="{{ route('profile.edit') }}" class="flex items-center justify-center p-2 my-1 rounded-lg hover:cursor-pointer hover:bg-gray-150">
        <div class="inline-block rounded-full bg-gray-150">
            @php
                $user = Auth::user();
            @endphp
            <x-profile-picture :user="$user" class="size-6" />
        </div>
    </a>
</aside>