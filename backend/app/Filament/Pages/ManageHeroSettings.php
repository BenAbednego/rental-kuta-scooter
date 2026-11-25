<?php

namespace App\Filament\Pages;

use App\Models\HeroSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class ManageHeroSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Pengaturan Hero';

    protected static ?string $title = 'Pengaturan Hero';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.manage-hero-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = HeroSetting::query()->first();
        $state = $setting?->attributesToArray() ?? [
            'type' => 'single_image',
            'slide_interval' => 5,
            'video_source' => 'upload',
        ];

        if ($setting?->youtube_id) {
            $state['youtube_url'] = 'https://www.youtube.com/watch?v='.$setting->youtube_id;
        }

        $this->form->fill($state);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Media Latar Hero')
                    ->description('Atur gambar atau video latar pada bagian hero halaman depan.')
                    ->schema([
                        Select::make('type')
                            ->label('Jenis Latar')
                            ->options([
                                'single_image' => 'Satu Gambar',
                                'slider' => 'Slide Gambar',
                                'video' => 'Video Background',
                            ])
                            ->required()
                            ->live(),

                        FileUpload::make('image')
                            ->label('Gambar Hero')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('hero/images')
                            ->helperText('Format JPG, PNG, atau WebP; validasi maksimal 5 MB. Batas PHP aktif terdeteksi 2 MB per file dan 8 MB per request. Ukuran yang disarankan 1920 × 1080 px.')
                            ->required(fn (Get $get): bool => $get('type') === 'single_image')
                            ->visible(fn (Get $get): bool => $get('type') === 'single_image'),

                        FileUpload::make('slides')
                            ->label('Gambar Slide')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->minFiles(2)
                            ->maxFiles(8)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('hero/slides')
                            ->helperText('Pilih 2 sampai 8 gambar JPG, PNG, atau WebP. Validasi maksimal 5 MB per gambar; batas PHP aktif 2 MB per file dan 8 MB per request. Disarankan 1920 × 1080 px.')
                            ->required(fn (Get $get): bool => $get('type') === 'slider')
                            ->visible(fn (Get $get): bool => $get('type') === 'slider'),

                        TextInput::make('slide_interval')
                            ->label('Interval Slide (detik)')
                            ->numeric()
                            ->integer()
                            ->minValue(2)
                            ->maxValue(15)
                            ->default(5)
                            ->required(fn (Get $get): bool => $get('type') === 'slider')
                            ->visible(fn (Get $get): bool => $get('type') === 'slider'),

                        Select::make('video_source')
                            ->label('Sumber Video')
                            ->options([
                                'upload' => 'Upload Video',
                                'youtube' => 'Link YouTube',
                            ])
                            ->live()
                            ->required(fn (Get $get): bool => $get('type') === 'video')
                            ->visible(fn (Get $get): bool => $get('type') === 'video'),

                        FileUpload::make('video_path')
                            ->label('File Video')
                            ->acceptedFileTypes(['video/mp4', 'video/webm'])
                            ->maxSize(30720)
                            ->disk('public')
                            ->directory('hero/videos')
                            ->helperText('Format MP4 atau WebM, maksimal 30 MB. Batas PHP aktif di server saat ini: upload_max_filesize 2 MB dan post_max_size 8 MB; file yang lebih besar perlu batas server lebih tinggi.')
                            ->required(fn (Get $get): bool => $get('type') === 'video' && $get('video_source') === 'upload')
                            ->visible(fn (Get $get): bool => $get('type') === 'video' && $get('video_source') === 'upload'),

                        FileUpload::make('poster')
                            ->label('Gambar Poster Video (Opsional)')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('hero/posters')
                            ->helperText('Format JPG, PNG, atau WebP; validasi maksimal 5 MB. Batas PHP aktif terdeteksi 2 MB per file dan 8 MB per request. Disarankan 1920 × 1080 px.')
                            ->visible(fn (Get $get): bool => $get('type') === 'video'),

                        TextInput::make('youtube_url')
                            ->label('Link YouTube')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=XXXXXXXXXXX')
                            ->helperText('Terima tautan youtube.com/watch?v=, youtu.be/, /embed/, atau /shorts/. ID video 11 karakter akan disimpan.')
                            ->required(fn (Get $get): bool => $get('type') === 'video' && $get('video_source') === 'youtube')
                            ->rules([
                                fn (): \Closure => function (string $attribute, mixed $value, \Closure $fail): void {
                                    if (filled($value) && HeroSetting::youtubeIdFromUrl((string) $value) === null) {
                                        $fail('Masukkan tautan YouTube yang valid dengan ID video 11 karakter.');
                                    }
                                },
                            ])
                            ->visible(fn (Get $get): bool => $get('type') === 'video' && $get('video_source') === 'youtube'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $youtubeUrl = $state['youtube_url'] ?? null;
        unset($state['youtube_url']);

        $state['youtube_id'] = $state['type'] === 'video' && $state['video_source'] === 'youtube'
            ? HeroSetting::youtubeIdFromUrl($youtubeUrl)
            : null;

        if ($state['type'] === 'video' && $state['video_source'] === 'youtube' && $state['youtube_id'] === null) {
            throw ValidationException::withMessages([
                'data.youtube_url' => 'Masukkan tautan YouTube yang valid dengan ID video 11 karakter.',
            ]);
        }

        $setting = HeroSetting::query()->first() ?? new HeroSetting;
        $setting->fill($state);
        $setting->save();

        Notification::make()
            ->title('Pengaturan Hero Berhasil Disimpan')
            ->body('Latar hero halaman depan telah diperbarui.')
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
