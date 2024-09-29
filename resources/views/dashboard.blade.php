<x-app-layout>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <livewire:layouts.sidebar />

        <!-- Chats -->
        <livewire:layouts.chat-layout />
        {{-- <div class="flex">
            @if($activeComponent === 'contacts')
                <livewire:contacts.show-contacts />
            @else
                <livewire:chats.show-chats />
            @endif
        </div> --}}

        <!-- Chat -->
        <livewire:chats.show-chat />
    </div>
</x-app-layout>
