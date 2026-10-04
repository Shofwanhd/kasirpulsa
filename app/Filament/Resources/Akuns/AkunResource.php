<?php

namespace App\Filament\Resources\Akuns;

use App\Filament\Resources\Akuns\Pages\ManageAkuns;
use App\Models\Akun;
use App\Models\KategoriAkun;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AkunResource extends Resource
{
    protected static ?string $model = Akun::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $recordTitleAttribute = 'Akun';

    protected static ?string $navigationLabel = 'Akun';

    protected static string|UnitEnum|null $navigationGroup = 'Akun';

    protected static ?string $title = 'Akun';

    protected static ?string $pluralModelLabel = 'Akun';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_akun_id')
                    ->required()
                    ->options(KategoriAkun::pluck('kategori', 'id')),
                TextInput::make('nama_akun')
                    ->required(),
                TextInput::make('saldo_awal')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Akun')
            ->columns([
                TextColumn::make('KategoriAkun.kategori')
                    ->label('Kategori Akun')
                    ->sortable(),
                TextColumn::make('nama_akun')
                    ->searchable(),
                TextColumn::make('saldo_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->stackedOnMobile()
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAkuns::route('/'),
        ];
    }
}
