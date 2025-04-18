<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipeResource\Pages;
use App\Filament\Resources\TipeResource\RelationManagers;
use App\Models\Tipe;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TipeResource extends Resource
{
    protected static ?string $model = Tipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Jenis Program';

    protected static ?string $slug = 'jenis-programs';

    protected static ?string $heading = 'Jenis Program';

    protected static ?string $title = 'Jenis Program';

    protected static ?string $label = 'Jenis Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_tipe')
                        ->label('Nama Program')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            if (($get('slug_tipe') ?? '') !== Str::slug($old)) {
                                return;
                            }
                            $set('slug_tipe', Str::slug($state));
                        })
                        ->required()
                        ->columnSpanFull(),
                
                Hidden::make('slug_tipe')
                        ->label('Slug tipe'),
                
                Textarea::make('deskripsi_tipe')
                        ->label('Deskripsi Program')
                        ->placeholder('Innovative Government Award adalah ...')
                        ->columnSpanFull()
                        ->rows(3)
                        ->required(),
        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_tipe')
                ->label('Nama Program')
                ->searchable()
                ->sortable()
                ->description(fn (Tipe $record): string => Str::words($record->deskripsi_tipe, 10), position: 'below')
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipes::route('/'),
            'create' => Pages\CreateTipe::route('/create'),
            // 'edit' => Pages\EditTipe::route('/{record}/edit'),
        ];
    }
    // Menggunakan successRedirectUrl di CreateAction
    public static function actions(): array
    {
        return [
            CreateAction::make()
                ->successRedirectUrl(route('tipes.index')), // Ganti dengan route yang sesuai
        ];
    }
}
