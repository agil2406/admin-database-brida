<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
 
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori';
    protected ?string $heading = 'Kategori';
    protected static ?string $title = 'Kategori';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('nama_kategori')
                    ->label('Nama kategori')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug_kategori') ?? '') !== Str::slug($old)) {
                            return;
                        }
                        $set('slug_kategori', Str::slug($state));
                    })
                    ->required()
                    ->columnSpanFull(),
            
            Hidden::make('slug_kategori')
                    ->label('Slug kategori'),
            
            Select::make('tipe_kategori')
                    ->label('Tipe Kategori')
                    ->options([
                        'inovasi' => 'Inovasi',
                        'riset' => 'Riset',
                    ])
                    ->columnSpanFull()
                    ->required(),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipe_kategori')
                    ->label('Tipe Kategori')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => Str::title($state)), // Membuat huruf awal tiap kata kapital
                ])
            ->filters([
                SelectFilter::make('tipe_kategori')
                ->options([
                    'inovasi' => 'Inovasi',
                    'riset' => 'Riset',
                ])
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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            // 'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
     // Menggunakan successRedirectUrl di CreateAction
     public static function actions(): array
     {
         return [
             CreateAction::make()
                 ->successRedirectUrl(route('kategoris.index')), // Ganti dengan route yang sesuai
         ];
     }
}
