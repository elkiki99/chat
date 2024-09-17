<div class="flex flex-col w-1/4 p-4 text-white bg-gray-100">
            
    <!-- User information -->
    <h2 class="mb-4 text-lg font-bold">{{ Auth::user()->name }}</h2>
    
    @forelse(Auth::user()->chats as $chat)
        <ul>
            <li>
                <a>{{ $chat->name }}</a>
            </li>
        </ul>
    @empty
        <p>No chats found.</p>
    @endforelse
</div>