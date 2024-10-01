<div class="flex min-h-[50vh] w-full">
    <aside class="w-1/3 p-6 bg-gray-200">
        <a class="flex flex-col hover:cursor-pointer">
            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                Summary
            </div>
        </a>
        <a class="flex flex-col hover:cursor-pointer">
            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                Multimedia
            </div>
        </a>
        <a class="flex flex-col hover:cursor-pointer">
            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                Files
            </div>
        </a>
        <a class="flex flex-col hover:cursor-pointer">
            <div class="p-2 hover:bg-gray-150 hover:rounded-lg">
                Groups
            </div>
        </a>
    </aside>

    <div class="flex flex-col w-3/4 gap-4 p-6">
        <div class="flex flex-col items-center space-y-2">
            <x-profile-picture :user="$user" class="size-16" />

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $user->name }}
            </h2>

            <h3 class="text-gray-900 text-md dark:text-gray-100">
                {{ $user->username }}
            </h3>

            <p class="text-gray-700 dark:text-gray-300">
                {{ $user->email }}
            </p>
        </div>

        <div class="space-y-2">
            @php 
                $authUserGroups = auth()->user()->chats()->where('is_group', true)->pluck('id');
                $sharedGroups = $user->chats()->where('is_group', true)->whereIn('id', $authUserGroups)->get();
            @endphp
            
            <p class="text-gray-500">Info: </p>
            <p>Available</p>

            <p>Groups: </p>
            <p>{{ $sharedGroups->name }}</p>
        </div>

        <div class="flex justify-between mt-auto">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Remove') }}
            </x-secondary-button>

            <x-danger-button x-on:click="$dispatch('close')">
                {{ __('Block') }}
            </x-danger-button>
        </div>
    </div>
</div>
