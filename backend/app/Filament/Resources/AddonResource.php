<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddonResource\Pages;
use App\Models\Addon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AddonResource extends Resource
{
    protected static ?string $model = Addon::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static ?string $navigationLabel = 'Add-ons / Perlengkapan';

    protected static ?string $modelLabel = 'Add-on';

    protected static ?string $pluralModelLabel = 'Add-ons';

    protected static ?string $navigationGroup = 'Rental Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Add-on (Bilingual)')
                    ->description('Masukkan nama dan penjelasan perlengkapan tambahan untuk pelanggan.')
                    ->schema([
                        Forms\Components\TextInput::make('name_id')
                            ->label('Nama Add-on (Indonesia)')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('contoh: Helm Tambahan (SNI)'),
                        Forms\Components\TextInput::make('name_en')
                            ->label('Nama Add-on (English)')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Additional Helmet (SNI)'),
                        Forms\Components\Textarea::make('description_id')
                            ->label('Deskripsi (Indonesia)')
                            ->rows(2)
                            ->placeholder('contoh: Helm standar SNI steril, bersih, dan wangi.'),
                        Forms\Components\Textarea::make('description_en')
                            ->label('Deskripsi (English)')
                            ->rows(2)
                            ->placeholder('e.g. Clean, sterilized SNI helmet for extra safety.'),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan Harga & Status')
                    ->schema([
                        Forms\Components\Hidden::make('price_type')
                            ->default('per_rental'),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Sewa')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(10000)
                            ->helperText('Tarif sewa untuk add-on ini.'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil tampil lebih dulu.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Jika nonaktif, add-on tidak akan muncul di form reservasi pelanggan.')
                            ->inline(false),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_id')
                    ->label('Nama Add-on')
                    ->description(fn (Addon $record): string => $record->name_en ?? '')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Sewa')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
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
            'index' => Pages\ListAddons::route('/'),
            'create' => Pages\CreateAddon::route('/create'),
            'edit' => Pages\EditAddon::route('/{record}/edit'),
        ];
    }
}
