<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotorResource\Pages;
use App\Models\Motor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MotorResource extends Resource
{
    protected static ?string $model = Motor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Fleet Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('brand')
                            ->label('Merek Motor')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(120),
                        Forms\Components\Select::make('category')
                            ->options([
                                'matic' => 'Matic / Skutik',
                                'premium' => 'Premium Maxi',
                                'sport' => 'Sport / Classic',
                            ])
                            ->required()
                            ->default('matic'),
                        Forms\Components\TextInput::make('cc')
                            ->label('Engine Capacity (CC)')
                            ->numeric()
                            ->suffix('cc'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active (Rented)',
                                'inactive' => 'Inactive (Disabled)',
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\TextInput::make('unit_count')
                            ->label('Total Physical Units')
                            ->required()
                            ->numeric()
                            ->default(1),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Deposit (IDR)')
                    ->schema([
                        Forms\Components\TextInput::make('price_daily')
                            ->label('Daily Rate')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_weekly')
                            ->label('Weekly Rate')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price_monthly')
                            ->label('Monthly Rate')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('deposit_amount')
                            ->label('Deposit Amount')
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(2),

                Forms\Components\Section::make('Descriptions (Bilingual)')
                    ->schema([
                        Forms\Components\Textarea::make('description_id')
                            ->label('Deskripsi (Bahasa Indonesia)')
                            ->rows(3),
                        Forms\Components\Textarea::make('description_en')
                            ->label('Description (English)')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Specifications')
                    ->schema([
                        Forms\Components\FileUpload::make('main_image')
                            ->label('Main Photo (Cutout / Clean Background)')
                            ->image()
                            ->directory('motors'),
                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Additional Gallery Photos')
                            ->image()
                            ->multiple()
                            ->directory('motors/gallery'),
                        Forms\Components\KeyValue::make('specs')
                            ->label('Technical Specifications')
                            ->keyLabel('Feature / Spec')
                            ->valueLabel('Value'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merek Motor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'matic' => 'Matic / Skutik',
                        'premium' => 'Premium Maxi',
                        'sport' => 'Sport / Classic',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('cc')
                    ->label('CC')
                    ->suffix(' cc')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_daily')
                    ->label('Daily Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_count')
                    ->label('Units')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'matic' => 'Matic / Skutik',
                        'premium' => 'Premium Maxi',
                        'sport' => 'Sport / Classic',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMotors::route('/'),
            'create' => Pages\CreateMotor::route('/create'),
            'edit' => Pages\EditMotor::route('/{record}/edit'),
        ];
    }
}
