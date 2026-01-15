<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="pt-4">
            <x-filament::button type="submit">
                {{ __('filament.profile.save') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
