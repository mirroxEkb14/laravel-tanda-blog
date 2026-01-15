<x-filament-panels::page>
    <form wire:submit="save">
        <div class="mb-6">
            {{ $this->form }}
        </div>

        <x-filament::button type="submit">
            {{ __('filament.profile.save') }}
        </x-filament::button>
    </form>
</x-filament-panels::page>
