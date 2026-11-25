<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryImageResource\Pages;
use App\Models\GalleryImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static ?string $navigationIcon = "heroicon-o-photo";

    protected static ?string $navigationLabel = "Gallery Foto";

    protected static ?string $navigationGroup = "Konten Website";

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Upload Gambar & Informasi")
                    ->schema([
                        Forms\Components\FileUpload::make("image_path")
                            ->label("Foto Galeri")
                            ->image()
                            ->disk("public")
                            ->directory("gallery")
                            ->imageResizeMode("cover")
                            ->imageCropAspectRatio("16:9")
                            ->required(),
                        Forms\Components\TextInput::make("caption")
                            ->label("Caption / Keterangan")
                            ->placeholder("Contoh: Antar motor NMAX di Bandara Ngurah Rai")
                            ->maxLength(255),
                        Forms\Components\TextInput::make("order")
                            ->label("Urutan Tampil (Order)")
                            ->numeric()
                            ->default(0)
                            ->helperText("Semakin kecil angka order, semakin di awal posisi fotonya."),
                        Forms\Components\Toggle::make("is_active")
                            ->label("Status Aktif")
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make("image_path")
                    ->label("Preview")
                    ->disk("public")
                    ->circular(false)
                    ->square(),
                Tables\Columns\TextColumn::make("caption")
                    ->label("Caption")
                    ->searchable()
                    ->limit(60)
                    ->weight("bold"),
                Tables\Columns\TextColumn::make("order")
                    ->label("Order")
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make("is_active")
                    ->label("Aktif")
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Tanggal Upload")
                    ->dateTime("d M Y")
                    ->sortable(),
            ])
            ->defaultSort("order", "asc")
            ->filters([
                Tables\Filters\TernaryFilter::make("is_active")
                    ->label("Status Aktif"),
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
            "index" => Pages\ListGalleryImages::route("/"),
            "create" => Pages\CreateGalleryImage::route("/create"),
            "edit" => Pages\EditGalleryImage::route("/{record}/edit"),
        ];
    }
}
