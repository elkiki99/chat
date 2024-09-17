<aside class="w-1/4 p-4 bg-white border-gray-300 border-x">
    <h2 class="text-lg font-semibold">Chats</h2>
    
    <ul>
        @forelse ($chats as $chat)
            <li>
                <a wire:click="selectChat({{ $chat->id }})" class="block p-2 rounded cursor-pointer hover:bg-gray-50">
                    {{ $chat->name }}
                </a>
            </li>
        @empty
            <li>No chats found</li>
        @endforelse
    </ul>
</aside>
