<!-- Chat message -->
<div
    class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} {{ $isLastInBlock ? 'mb-3' : 'mb-1' }}">
    @if ($chat->is_group && $message->user_id !== Auth::id() && $isFirstInBlock)
        <!-- User profile picture outside the message -->
        <x-profile-picture :user="$message->user" class="mr-3 size-8" />
    @endif

    <!-- Message details -->
    <div
        class="relative max-w-sm p-2 text-sm rounded shadow
                            {{ $message->user_id === Auth::id() ? 'bg-green-200 text-gray-800' : 'bg-white text-gray-800' }}
                            {{ !$isFirstInBlock && $chat->is_group ? 'ml-11' : '' }}">
        <!-- Flex container for text and time/check -->
        <div class="flex h-full gap-5">
            <!-- Flex container for message and time -->
            <div class="flex items-start flex-1 gap-3">
                @if ($chat->is_group)
                    <!-- Message details -->
                    @if ($message->user_id !== Auth::id() && $chat->is_group && $isFirstInBlock)
                        <div class="flex flex-col flex-1">
                            <span class="font-semibold">{{ $message->user->name }}</span>
                            <span>{{ $message->body }}</span>
                        </div>
                    @else
                        <div class="flex flex-col flex-1">
                            <span>{{ $message->body }}</span>
                        </div>
                    @endif
                @else
                    <!-- Message details -->
                    <div class="flex flex-col flex-1">
                        <span>{{ $message->body }}</span>
                    </div>
                @endif
            </div>

            <!-- Time and check icons -->
            <div class="flex items-center self-end gap-1 mt-1 text-xs text-gray-500">
                <span>{{ $message->created_at->format('H:i') }}</span>
                @if ($message->user_id === Auth::id())
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                @endif
            </div>
        </div>
    </div>
</div>
