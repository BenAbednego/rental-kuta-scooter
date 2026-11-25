<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Testimoni Pelanggan';

    protected static ?string $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rating')
                            ->label('Rating Bintang')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->default(5),
                    ])->columns(3),

                Forms\Components\Section::make('Isi Testimoni (Bilingual)')
                    ->schema([
                        Forms\Components\Textarea::make('text_id')
                            ->label('Testimoni (Bahasa Indonesia)')
                            ->required()
                            ->rows(4),
                        Forms\Components\Textarea::make('text_en')
                            ->label('Testimonial (English)')
                            ->required()
                            ->rows(4),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan Tampilan')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan Tampil (Order)')
                            ->numeric()
                            ->default(0)
                            ->helperText('Semakin kecil angka order, semakin atas posisinya.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Matikan untuk menyembunyikan testimoni dari website.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state))
                    ->color('warning'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
