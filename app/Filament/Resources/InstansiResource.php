<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstansiResource\Pages;
use App\Filament\Resources\InstansiResource\RelationManagers;
use App\Models\Instansi;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class InstansiResource extends Resource
{
    protected static ?string $model = Instansi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
 
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_instansi')
                    ->label('Nama Instansi')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug_instansi') ?? '') !== Str::slug($old)) {
                            return;
                        }
                        $set('slug_instansi', Str::slug($state));
                    })
                    ->required()
                    ->columnSpanFull(),
            
            Hidden::make('slug_instansi')
                    ->label('Slug Instansi'),
            
            Textarea::make('alamat_instansi')
                    ->label('Alamat Instansi')
                    ->placeholder('Masukkan alamat lengkap')
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->rows(3)
                    ->required(),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_instansi')
                ->label('Nama Instansi')
                ->searchable()
                ->sortable()
                ->description(fn (Instansi $record): string => Str::words($record->alamat_instansi,10), position: 'below')
            ])
            ->filters([
                //
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
            'index' => Pages\ListInstansis::route('/'),
            'create' => Pages\CreateInstansi::route('/create'),
            // 'edit' => Pages\EditInstansi::route('/{record}/edit'),
        ];
    }
    // Menggunakan successRedirectUrl di CreateAction
    public static function actions(): array
    {
        return [
            CreateAction::make()
                ->successRedirectUrl(route('instansis.index')), // Ganti dengan route yang sesuai
        ];
    }
}
