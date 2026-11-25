<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = "heroicon-o-users";

    protected static ?string $navigationLabel = "Admin Users";

    protected static ?string $navigationGroup = "Pengaturan Sistem";

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Akun Admin")
                    ->description("Kelola akses login staf dan administrator dashboard.")
                    ->schema([
                        Forms\Components\TextInput::make("name")
                            ->label("Nama Lengkap Admin")
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make("email")
                            ->label("Alamat Email")
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make("password")
                            ->label("Password Akun")
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === "create")
                            ->helperText("Kosongkan jika tidak ingin mengubah password saat edit data.")
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label("Nama Admin")
                    ->searchable()
                    ->sortable()
                    ->weight("bold"),
                Tables\Columns\TextColumn::make("email")
                    ->label("Email Login")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Dibuat Pada")
                    ->dateTime("d M Y H:i")
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (User $record) => $record->id === auth()->id()),
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
            "index" => Pages\ListUsers::route("/"),
            "create" => Pages\CreateUser::route("/create"),
            "edit" => Pages\EditUser::route("/{record}/edit"),
        ];
    }
}
