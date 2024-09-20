<!-- Chat message -->
<div
    class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }} {{ $isLastInBlock ? 'mb-3' : 'mb-1' }}">

    <!-- Profile picture for group chats if it's the first message in the block -->
    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
        <x-profile-picture :user="$message->user" class="mr-3 size-8" />
    @endif

    <!-- Message details -->
    <div
        class="relative max-w-sm p-2 text-sm rounded shadow
                {{ $isCurrentUser ? 'bg-green-200 text-gray-800' : 'bg-white text-gray-800' }}
                {{ !$isFirstInBlock && $chat->is_group ? 'ml-11' : '' }}">

        <!-- Flex container for text and time/check -->
        <div class="flex h-full gap-5">

            <!-- Message content -->
            <div class="flex items-start flex-1 gap-3">
                <div class="flex flex-col flex-1">
                    <!-- Show username only in group chat and if it's the first message in the block -->
                    @if ($chat->is_group && !$isCurrentUser && $isFirstInBlock)
                        <span class="font-semibold">{{ $message->user->name }}</span>
                    @endif
                    <span>{{ $message->body }}</span>
                </div>
            </div>

            <!-- Time and check icons -->
            <div class="flex items-center self-end gap-1 mt-1 text-xs text-gray-500">
                <span>{{ $message->created_at->format('H:i') }}</span>
                @if ($isCurrentUser)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                @endif
            </div>
        </div>
    </div>
</div>
