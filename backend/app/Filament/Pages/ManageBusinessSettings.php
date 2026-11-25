<?php

namespace App\Filament\Pages;

use App\Models\BusinessSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageBusinessSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Bisnis';

    protected static ?string $title = 'Pengaturan Bisnis';

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-business-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = BusinessSetting::firstOrCreate(
            ['id' => 1],
            [
                'whatsapp_primary' => '6285947167662',
                'whatsapp_secondary' => null,
                'business_email' => 'info@sewamotorkuta.id',
                'business_address' => 'Jl. Pantai Kuta No. 88, Badung, Bali 80361',
                'operating_hours_id' => 'Setiap Hari (Senin - Minggu): 08:00 - 22:00 WITA',
                'operating_hours_en' => 'Everyday (Monday - Sunday): 08:00 - 22:00 WITA',
                'instagram_url' => 'https://instagram.com/rmb_kutabali',
            ]
        );

        $this->form->fill($setting->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kontak WhatsApp & Online')
                    ->description('Nomor kontak utama untuk pemesanan langsung, floating WhatsApp, footer, dan pesan booking.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('whatsapp_primary')
                                ->label('Nomor WhatsApp Utama')
                                ->helperText('Format internasional tanpa tanda plus (+), contoh: 6285947167662')
                                ->required()
                                ->maxLength(30),
                            TextInput::make('whatsapp_secondary')
                                ->label('Nomor WhatsApp Sekunder (Opsional)')
                                ->helperText('Nomor cadangan jika nomor utama sibuk')
                                ->nullable()
                                ->maxLength(30),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('business_email')
                                ->label('Email Bisnis')
                                ->email()
                                ->nullable()
                                ->maxLength(100),
                            TextInput::make('instagram_url')
                                ->label('URL Profil Instagram')
                                ->url()
                                ->nullable()
                                ->placeholder('https://instagram.com/...'),
                        ]),
                    ]),

                Section::make('Alamat Garasi & Jam Operasional')
                    ->description('Informasi fisik garasi rental dan jam operasional untuk ditampilkan di halaman kontak & footer.')
                    ->schema([
                        Textarea::make('business_address')
                            ->label('Alamat Lengkap Garasi')
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('operating_hours_id')
                                ->label('Jam Operasional (Indonesia)')
                                ->helperText('Tampil pada antarmuka Bahasa Indonesia')
                                ->required()
                                ->maxLength(150),
                            TextInput::make('operating_hours_en')
                                ->label('Jam Operasional (Inggris)')
                                ->helperText('Tampil pada antarmuka Bahasa Inggris')
                                ->required()
                                ->maxLength(150),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        BusinessSetting::updateOrCreate(
            ['id' => 1],
            $state
        );

        Notification::make()
            ->title('Pengaturan Bisnis Berhasil Disimpan')
            ->body('Seluruh informasi kontak dan nomor WhatsApp di website telah diperbarui secara langsung.')
            ->success()
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
