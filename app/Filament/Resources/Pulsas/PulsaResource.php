<?php

namespace App\Filament\Resources\Pulsas;

use App\Filament\Resources\Pulsas\Pages\ManagePulsas;
use App\Models\KategoriPulsa;
use App\Models\Pulsa;
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

class PulsaResource extends Resource
{
    protected static ?string $model = Pulsa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboard;

    protected static ?string $recordTitleAttribute = 'Pulsa';

    protected static string|UnitEnum|null $navigationGroup = 'Akun';

    protected static ?string $title = 'Pulsa';

    protected static ?string $pluralModelLabel = 'Pulsa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_pulsa_id')
                    ->label('Kategori Pulsa')
                    ->required()
                    ->options(function () {
                        return KategoriPulsa::pluck('kategori', 'id');
                    }),
                TextInput::make('pulsa')
                    ->required(),
                TextInput::make('harga')
                    ->label('Harga Modal')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Pulsa')
            ->columns([
                TextColumn::make('kategoriPulsa.kategori')
                    ->label('Kategori')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pulsa')
                    ->searchable(),
                TextColumn::make('harga')
                    ->label('Modal')
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
            ])
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
            'index' => ManagePulsas::route('/'),
        ];
    }
}
