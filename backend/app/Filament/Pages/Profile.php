<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Profile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.profile';

    public array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('filament.profile.navigation_label');
    }

    public function getTitle(): string
    {
        return __('filament.profile.navigation_label');
    }

    public function mount(): void
    {
        $this->form->fill([
            'locale' => auth()->user()?->locale ?? config('app.locale'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('locale')
                    ->label(__('filament.profile.language_label'))
                    ->options([
                        'en' => 'English',
                        'ru' => 'Русский',
                    ])
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $user->forceFill([
            'locale' => $data['locale'],
        ])->save();

        Notification::make()
            ->title(__('filament.profile.saved'))
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }
}
