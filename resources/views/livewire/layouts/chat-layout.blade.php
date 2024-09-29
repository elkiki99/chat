<div class="flex">
    @if($activeComponent === 'contacts')
        <livewire:contacts.show-contacts />
    @else
        <livewire:chats.show-chats />
    @endif
</div>