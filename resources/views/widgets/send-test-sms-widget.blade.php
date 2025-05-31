<x-filament::widget>
    <x-filament::card>
        <form wire:submit.prevent="send">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-4">
                Send SMS
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::widget>
