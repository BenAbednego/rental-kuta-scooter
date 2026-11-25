<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Motor;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Rental Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer & Vehicle Details')
                    ->schema([
                        Forms\Components\Select::make('motor_id')
                            ->relationship('motor', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('customer_name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_whatsapp')
                            ->label('WhatsApp Number')
                            ->placeholder('e.g. +628123456789')
                            ->maxLength(30),
                        Forms\Components\Select::make('customer_document_type')
                            ->label('ID Document Type')
                            ->placeholder('-')
                            ->options([
                                'passport' => 'Passport',
                                'KTP' => 'KTP (National ID)',
                                'SIM' => 'Driver License (SIM)',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Rental Dates & Calculation')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::recalculateBooking($get, $set);
                            }),
                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::recalculateBooking($get, $set);
                            }),
                        Forms\Components\TextInput::make('total_days')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(2),

                Forms\Components\Section::make('Add-ons / Perlengkapan Tambahan')
                    ->description('Perlengkapan atau aksesoris tambahan yang disewa pelanggan.')
                    ->schema([
                        Forms\Components\Repeater::make('addons')
                            ->schema([
                                Forms\Components\Select::make('id')
                                    ->label('Pilih Add-on')
                                    ->options(\App\Models\Addon::where('is_active', true)->pluck('name_id', 'id'))
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($addon = \App\Models\Addon::find($state)) {
                                            $set('name', $addon->name_id);
                                            $set('price', (float) $addon->price);
                                        }
                                    })
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->readOnly(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Sewa')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                            ])
                            ->columns(4)
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::recalculateBooking($get, $set);
                            }),
                    ]),

                Forms\Components\Section::make('Status & Admin Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Confirmation',
                                'confirmed' => 'Confirmed',
                                'ongoing' => 'Ongoing Rental',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'blocked' => 'Blocked / Maintenance',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Customer / Internal Notes')
                            ->rows(3),
                    ])->columns(2),
            ]);
    }

    protected static function recalculateBooking(Forms\Get $get, Forms\Set $set): void
    {
        $start = $get('start_date');
        $end = $get('end_date');
        $motorId = $get('motor_id');
        $addons = $get('addons') ?? [];

        if ($start && $end) {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);
            $days = max(1, $startDate->diffInDays($endDate));
            $set('total_days', $days);

            $motor = $motorId ? Motor::find($motorId) : null;
            $motorDaily = $motor ? (float) $motor->price_daily : 0;
            $motorTotal = $days * $motorDaily;

            $addonsTotal = 0;
            foreach ($addons as $addon) {
                $qty = (int) ($addon['quantity'] ?? 1);
                $price = (float) ($addon['price'] ?? 0);
                $addonSub = $price * $qty;
                $addonsTotal += $addonSub;
            }

            $set('addons_total', $addonsTotal);
            $set('total_price', $motorTotal + $addonsTotal);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('motor.name')
                    ->label('Motor Vehicle')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('customer_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('customer_document_type')
                    ->label('ID Document')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'ongoing' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'blocked' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_days')
                    ->label('Days')
                    ->suffix(' days'),
                Tables\Columns\TextColumn::make('addons')
                    ->label('Add-ons')
                    ->formatStateUsing(function ($state) {
                        if (empty($state) || !is_array($state)) return '-';
                        return collect($state)->map(fn($a) => ($a['quantity'] ?? 1).'x '.($a['name'] ?? 'Item'))->join(', ');
                    })
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'blocked' => 'Blocked',
                    ]),
                Tables\Filters\SelectFilter::make('motor')
                    ->relationship('motor', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('Chat WA')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => filled($record->customer_whatsapp))
                    ->url(fn (Booking $record): string => 'https://wa.me/'.$record->clean_whatsapp.'?text='.urlencode("Halo {$record->customer_name}, mengenai booking motor #{$record->id} ({$record->motor?->name})..."))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
