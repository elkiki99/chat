{{-- <x-modal maxWidth="sm" name="send-file" focusable>
    <div class="p-6 space-y-2">
        <p>Send files</p>
        <livewire:dropzone wire:model="file" :multiple="true" />
        <x-primary-button wire:click="sendFile" class="mt-4">Send</x-primary-button>
        @if ($errors->has('files.*'))
            <div class="text-red-500">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</x-modal> --}}
