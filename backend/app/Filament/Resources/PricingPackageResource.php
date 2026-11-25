<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingPackageResource\Pages;
use App\Models\PricingPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PricingPackageResource extends Resource
{
    protected static ?string $model = PricingPackage::class;

    protected static ?string $navigationIcon = "heroicon-o-tag";

    protected static ?string $navigationLabel = "Pricing Packages";

    protected static ?string $navigationGroup = "Konten Website";

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Informasi Paket & Harga (Bilingual)")
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make("name_id")
                                    ->label("Nama Paket (ID)")
                                    ->placeholder("Contoh: Paket Mingguan")
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make("name_en")
                                    ->label("Package Name (EN)")
                                    ->placeholder("Example: Weekly Package")
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make("subtitle_id")
                                    ->label("Deskripsi Singkat (ID)")
                                    ->placeholder("Contoh: Paling hemat untuk liburan 7 hari.")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make("subtitle_en")
                                    ->label("Short Description (EN)")
                                    ->placeholder("Example: Best value for 7 days holiday.")
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make("price")
                                    ->label("Harga Sewa (IDR)")
                                    ->numeric()
                                    ->prefix("Rp")
                                    ->required(),
                                Forms\Components\TextInput::make("price_unit_id")
                                    ->label("Satuan Waktu (ID)")
                                    ->default("/ minggu")
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\TextInput::make("price_unit_en")
                                    ->label("Time Unit (EN)")
                                    ->default("/ week")
                                    ->required()
                                    ->maxLength(50),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make("cta_label_id")
                                    ->label("Teks Tombol CTA (ID)")
                                    ->default("Sewa Mingguan")
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make("cta_label_en")
                                    ->label("CTA Button Label (EN)")
                                    ->default("Rent Weekly")
                                    ->required()
                                    ->maxLength(100),
                            ]),
                    ]),

                Forms\Components\Section::make("Ikon & Tampilan Kartu")
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make("icon")
                                    ->label("Ikon Lucide")
                                    ->options([
                                        "Bike" => "Bike (Motor / Standar)",
                                        "Sparkles" => "Sparkles (Bintang Berkilau / Populer)",
                                        "Crown" => "Crown (Mahkota / Premium)",
                                        "Zap" => "Zap (Petir / Cepat)",
                                        "Shield" => "Shield (Perisai / Keamanan)",
                                        "Clock" => "Clock (Jam / 24 Jam)",
                                        "Star" => "Star (Bintang)",
                                    ])
                                    ->default("Bike")
                                    ->required(),
                                Forms\Components\TextInput::make("order")
                                    ->label("Urutan Tampil (Order)")
                                    ->numeric()
                                    ->default(0)
                                    ->helperText("Semakin kecil angka order, semakin di kiri posisi kartunya."),
                                Forms\Components\Toggle::make("is_featured")
                                    ->label("Highlight / Paling Populer")
                                    ->helperText("Beri bingkai terracotta dan badge Paling Populer")
                                    ->default(false),
                            ]),
                        Forms\Components\Toggle::make("is_active")
                            ->label("Status Aktif")
                            ->default(true),
                    ]),

                Forms\Components\Section::make("Daftar Benefit / Keunggulan (Repeater)")
                    ->description("Admin dapat menambah, mengubah, atau menghapus poin benefit dengan mudah.")
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Repeater::make("benefits_id")
                                    ->label("Poin Keunggulan (Bahasa Indonesia)")
                                    ->simple(
                                        Forms\Components\TextInput::make("item")
                                            ->placeholder("Contoh: 2 Helm Higienis SNI")
                                            ->required()
                                    )
                                    ->addActionLabel("+ Tambah Poin Benefit (ID)")
                                    ->reorderable()
                                    ->required(),
                                Forms\Components\Repeater::make("benefits_en")
                                    ->label("Benefits Points (English)")
                                    ->simple(
                                        Forms\Components\TextInput::make("item")
                                            ->placeholder("Example: 2 Sanitized SNI Helmets")
                                            ->required()
                                    )
                                    ->addActionLabel("+ Add Benefit Point (EN)")
                                    ->reorderable()
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name_id")
                    ->label("Paket (ID)")
                    ->searchable()
                    ->weight("bold"),
                Tables\Columns\TextColumn::make("name_en")
                    ->label("Package (EN)")
                    ->searchable()
                    ->color("gray"),
                Tables\Columns\TextColumn::make("price")
                    ->label("Harga")
                    ->money("IDR")
                    ->sortable(),
                Tables\Columns\TextColumn::make("price_unit_id")
                    ->label("Satuan"),
                Tables\Columns\TextColumn::make("icon")
                    ->label("Icon")
                    ->badge()
                    ->color("info"),
                Tables\Columns\IconColumn::make("is_featured")
                    ->label("Populer")
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make("order")
                    ->label("Order")
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make("is_active")
                    ->label("Aktif")
                    ->boolean()
                    ->alignCenter(),
            ])
            ->defaultSort("order", "asc")
            ->filters([
                Tables\Filters\TernaryFilter::make("is_active")
                    ->label("Status Aktif"),
                Tables\Filters\TernaryFilter::make("is_featured")
                    ->label("Status Populer / Featured"),
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
            "index" => Pages\ListPricingPackages::route("/"),
            "create" => Pages\CreatePricingPackage::route("/create"),
            "edit" => Pages\EditPricingPackage::route("/{record}/edit"),
        ];
    }
}
