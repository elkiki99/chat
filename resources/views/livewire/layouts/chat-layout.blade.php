<div class="flex">
    @if($activeComponent === 'contacts')
        <livewire:contacts.show-contacts />
    @elseif($activeComponent === 'chats')
        <livewire:chats.show-chats />
    @elseif($activeComponent === 'archived')
        <livewire:chats.show-archived />
    @endif
</div>