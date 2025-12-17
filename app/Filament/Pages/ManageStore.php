<?php

namespace App\Filament\Pages;

use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageStore extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Toko';
    protected static ?string $title = 'Kelola Pengaturan Toko';

    protected static string $view = 'filament.pages.manage-store';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = StoreSetting::all()->pluck('value', 'key')->toArray();
        
        $this->form->fill([
            'wa_number' => $settings['wa_number'] ?? '',
            'is_store_open' => ($settings['is_store_open'] ?? 'false') === 'true',
            'instagram_url' => $settings['instagram_url'] ?? '',
            'tiktok_url' => $settings['tiktok_url'] ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pengaturan Umum')
                    ->schema([
                        TextInput::make('wa_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Format: 628xxxxxxxxxx')
                            ->required(),
                        Toggle::make('is_store_open')
                            ->label('Status Buka Toko')
                            ->helperText('Aktifkan untuk BUKA, matikan untuk TUTUP (Mode Pre-Order).')
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('instagram_url')
                            ->label('Link Instagram')
                            ->placeholder('https://instagram.com/...')
                            ->url(),
                        TextInput::make('tiktok_url')
                            ->label('Link TikTok')
                            ->placeholder('https://tiktok.com/...')
                            ->url(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        StoreSetting::updateOrCreate(
            ['key' => 'wa_number'],
            ['value' => $data['wa_number']]
        );

        StoreSetting::updateOrCreate(
            ['key' => 'is_store_open'],
            ['value' => $data['is_store_open'] ? 'true' : 'false']
        );

        StoreSetting::updateOrCreate(
            ['key' => 'instagram_url'],
            ['value' => $data['instagram_url'] ?? null]
        );

        StoreSetting::updateOrCreate(
            ['key' => 'tiktok_url'],
            ['value' => $data['tiktok_url'] ?? null]
        );

        Notification::make()
            ->success()
            ->title('Pengaturan berhasil disimpan')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }
}
