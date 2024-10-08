<!-- Bottom actions -->
<aside class="flex flex-col w-12 p-2 text-white bg-gray-100">
    <!-- Chats -->
    <div class="space-y-1">
        <a wire:click="selectChats()" class="flex flex-col items-center hover:cursor-pointer">
            <div
                class="p-2 hover:bg-gray-150 hover:rounded-lg {{ $activeComponent == 'chats' ? 'bg-gray-200 hover:rounded-lg rounded-lg' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="text-gray-800 size-6 ">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
            </div>
        </a>

        <!-- Contacts -->
        <a wire:click="selectContacts()" class="flex flex-col items-center hover:cursor-pointer ">
            <div
                class="p-2 hover:bg-gray-150 hover:rounded-lg {{ $activeComponent == 'contacts' ? 'bg-gray-200 hover:rounded-lg rounded-lg' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="text-gray-800 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Bottom actions -->
    <div class="flex flex-col items-center mt-auto hover:cursor-pointer">
        <!-- Archived -->
        <a wire:click="selectArchived()" class="p-1">
            <div
                class="p-2 my-0.5 hover:bg-gray-150 hover:rounded-lg {{ $activeComponent == 'archived' ? 'bg-gray-200 hover:rounded-lg rounded-lg' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="text-gray-800 size-6 ">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
        </a>

        <!-- Logout -->
        <div class="p-2 hover:bg-gray-150 hover:rounded-lg ">
            <form method="POST" action="{{ route('logout') }}" class="flex items-center justify-center">
                @csrf
                <button type="submit" class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="text-gray-800 size-6 hover:cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m19.5 4.5-15 15m0 0h11.25m-11.25 0V8.25" />
                    </svg>
                </button>
            </form>
        </div>

        <div class="p-2 space-y-1">
            <!-- Profile -->
            <div class="px-2 pt-2 hover:bg-gray-150 hover:rounded-lg">
                <a 
                    {{-- x-on:click="$dispatch('open-modal', 'profile')" --}}
                    wire:navigate href="{{ route('profile.edit') }}"
                >
                    <div class="inline-block rounded-full bg-gray-150">
                        @php
                            $user = Auth::user();
                        @endphp
                        <x-profile-picture :user="$user" class="size-6" />
                    </div>
                </a>

                <x-modal name="profile" maxWidth="md" focusable>
                    <x-app-layout>
                        <x-slot name="header">
                            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                {{ __('Profile') }}
                            </h2>
                        </x-slot>
                    
                        <div class="py-12">
                            <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                                    <div class="max-w-xl">
                                        @include('profile.partials.update-profile-information-form')
                                    </div>
                                </div>
                    
                                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                                    <div class="max-w-xl">
                                        @include('profile.partials.update-password-form')
                                    </div>
                                </div>
                    
                                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                                    <div class="max-w-xl">
                                        @include('profile.partials.delete-user-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-app-layout>
                    
                </x-modal>
            </div>
        </div>
    </div>
</aside>
