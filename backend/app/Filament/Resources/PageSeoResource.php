<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSeoResource\Pages;
use App\Models\PageSeo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageSeoResource extends Resource
{
    protected static ?string $model = PageSeo::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Pengaturan SEO';

    protected static ?string $modelLabel = 'SEO Halaman';

    protected static ?string $pluralModelLabel = 'Pengaturan SEO';

    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Bahasa SEO')
                ->tabs([
                    Tabs\Tab::make('Indonesia')
                        ->schema([
                            TextInput::make('title_id')
                                ->label('Title (Indonesia)')
                                ->maxLength(60)
                                ->live(debounce: 300)
                                ->hint(fn (?string $state): string => mb_strlen($state ?? '').'/60 karakter'),
                            Textarea::make('description_id')
                                ->label('Description (Indonesia)')
                                ->rows(4)
                                ->maxLength(160)
                                ->live(debounce: 300)
                                ->hint(fn (?string $state): string => mb_strlen($state ?? '').'/160 karakter'),
                        ]),
                    Tabs\Tab::make('English')
                        ->schema([
                            TextInput::make('title_en')
                                ->label('Title (English)')
                                ->maxLength(60)
                                ->live(debounce: 300)
                                ->hint(fn (?string $state): string => mb_strlen($state ?? '').'/60 karakter'),
                            Textarea::make('description_en')
                                ->label('Description (English)')
                                ->rows(4)
                                ->maxLength(160)
                                ->live(debounce: 300)
                                ->hint(fn (?string $state): string => mb_strlen($state ?? '').'/160 karakter'),
                        ]),
                ])
                ->columnSpanFull(),
            FileUpload::make('featured_image')
                ->label('Featured Image (og:image)')
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->maxSize(2048)
                ->disk('public')
                ->directory('page-seo')
                ->helperText('Disarankan 1200x630 px.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_key')
                    ->label('Halaman')
                    ->formatStateUsing(fn (string $state): string => config("seo_pages.pages.{$state}.label", $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title_id')
                    ->label('Title (Indonesia)')
                    ->limit(45)
                    ->placeholder('Menggunakan bawaan'),
                Tables\Columns\TextColumn::make('title_en')
                    ->label('Title (English)')
                    ->limit(45)
                    ->placeholder('Menggunakan bawaan'),
            ])
            ->defaultSort('page_key')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('page_key', array_keys(config('seo_pages.pages', [])));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageSeos::route('/'),
            'edit' => Pages\EditPageSeo::route('/{record}/edit'),
        ];
    }
}
