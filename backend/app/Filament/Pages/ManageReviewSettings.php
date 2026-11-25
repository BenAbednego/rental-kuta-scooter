<?php

namespace App\Filament\Pages;

use App\Models\ReviewSetting;
use App\Services\IframeParser;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class ManageReviewSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Pengaturan Review';

    protected static ?string $title = 'Pengaturan Review';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.manage-review-settings';

    public ?array $data = [];

    public ?array $iframeReading = null;

    public ?string $iframeError = null;

    public function mount(): void
    {
        $setting = ReviewSetting::query()->first();
        $state = $setting?->attributesToArray() ?? ['display_mode' => 'manual'];

        if ($setting?->display_mode === 'iframe' && $setting->iframe_src) {
            $state['iframe_code'] = $this->buildIframeCode($setting);
            $this->readIframe($state['iframe_code']);
        }

        $this->form->fill($state);
    }

    public function updatedData(mixed $value, string $key): void
    {
        if ($key === 'iframe_code') {
            $this->readIframe(is_string($value) ? $value : '');
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tampilan Bagian Review')
                    ->schema([
                        Select::make('display_mode')
                            ->label('Mode Tampilan')
                            ->options([
                                'manual' => 'Manual',
                                'iframe' => 'Iframe',
                            ])
                            ->required()
                            ->live(),

                        Placeholder::make('manual_help')
                            ->label('Pengelolaan Testimoni')
                            ->content('Mode Manual menggunakan kartu dari menu "Testimoni Pelanggan". Kelola isi kartu melalui menu tersebut.')
                            ->visible(fn (Get $get): bool => $get('display_mode') === 'manual'),

                        Textarea::make('iframe_code')
                            ->label('Kode Iframe')
                            ->rows(5)
                            ->placeholder('<iframe src="https://..." title="..." width="600" height="450"></iframe>')
                            ->helperText('Tempel satu tag iframe murni. Script/widget tidak didukung.')
                            ->live(onBlur: true)
                            ->required(fn (Get $get): bool => $get('display_mode') === 'iframe')
                            ->visible(fn (Get $get): bool => $get('display_mode') === 'iframe'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(IframeParser $parser): void
    {
        $state = $this->form->getState();
        $attributes = ['display_mode' => $state['display_mode']];

        if ($state['display_mode'] === 'iframe') {
            try {
                $parsed = $parser->parse((string) ($state['iframe_code'] ?? ''));
            } catch (InvalidArgumentException $exception) {
                $this->iframeError = $exception->getMessage();
                throw ValidationException::withMessages([
                    'data.iframe_code' => $exception->getMessage(),
                ]);
            }

            $attributes += [
                'iframe_src' => $parsed['src'],
                'iframe_provider' => $parsed['provider_label'],
                'iframe_title' => $parsed['title'],
                'iframe_width' => $parsed['width'],
                'iframe_height' => $parsed['height'],
            ];

            $this->iframeReading = $parsed;
            $this->iframeError = null;
        }

        $setting = ReviewSetting::query()->first() ?? new ReviewSetting;
        $setting->fill($attributes);
        $setting->save();

        Notification::make()
            ->title('Pengaturan Review Berhasil Disimpan')
            ->body('Mode tampilan bagian review telah diperbarui.')
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

    private function readIframe(string $code): void
    {
        if (trim($code) === '') {
            $this->iframeReading = null;
            $this->iframeError = null;

            return;
        }

        try {
            $this->iframeReading = app(IframeParser::class)->parse($code);
            $this->iframeError = null;
        } catch (InvalidArgumentException $exception) {
            $this->iframeReading = null;
            $this->iframeError = $exception->getMessage();
        }
    }

    private function buildIframeCode(ReviewSetting $setting): string
    {
        return '<iframe src="'.e($setting->iframe_src).'"'
            .' title="'.e($setting->iframe_title ?? '').'"'
            .' width="'.($setting->iframe_width ?? '').'"'
            .' height="'.($setting->iframe_height ?? '').'"></iframe>';
    }
}
