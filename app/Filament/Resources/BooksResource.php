<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BooksResource\Pages;
use App\Filament\Resources\BooksResource\RelationManagers;
use App\Models\Books;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BooksResource extends Resource
{
    protected static ?string $model = Books::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku';
    
    protected static ?string $heading = 'Buku';

    protected static ?string $title = 'Buku';

    protected static ?string $label = 'Buku';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                    ->label('Form Buku')
                    ->steps([
                        // Step 1
                        Wizard\Step::make('Informasi Buku')
                            ->schema([
                                TextInput::make('judul')
                                ->label('Judul Buku')
                                ->placeholder('Judul Buku')
                                ->helperText('Masukkan Judul Buku.')
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                    // Jika judul tidak berubah, tidak perlu melakukan apa-apa
                                    if (($get('slug_buku') ?? '') !== Str::slug($old)) {
                                        return;
                                    }
                            
                                    // Membuat slug dari judul yang diinputkan
                                    $slug = Str::slug($state);
                            
                                    // Menambahkan angka jika slug sudah ada di database
                                    $existingCount = Books::where('slug_buku', 'like', $slug . '%')->count();
                                    
                                    if ($existingCount > 0) {
                                        $slug .= '-' . ($existingCount + 1); // Menambahkan angka agar slug unik
                                    }
                            
                                    // Menyimpan slug yang telah diubah ke field 'slug_buku'
                                    $set('slug_buku', $slug);
                                }),
                            
                                Hidden::make('slug_buku')
                                        ->label('Slug buku'),

                                Textarea::make('sinopsis')
                                    ->label('Sinopsis Buku')
                                    ->placeholder('Sinopsis Buku')
                                    ->helperText('Masukkan sinopsis buku.')
                                    ->rows(3)
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('penulis')
                                    ->label('Penulis')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan nama penulis buku.')
                                    ->placeholder('Penulis Buku'),

                                TextInput::make('penerbit')
                                    ->label('Penerbit')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan nama penerbit buku.')
                                    ->placeholder('Penerbit Buku'),

                                TextInput::make('isbn')
                                    ->label('ISBN')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan ISBN buku.')
                                    ->placeholder('ISBN Buku'),

                                DatePicker::make('tanggal_terbit')
                                    ->native(false)
                                    ->label('Tanggal Terbit')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan tanggal terbit buku.')
                                    ->placeholder('Tanggal Terbit Buku'),

                                TextInput::make('jumlah_halaman')
                                    ->label('Jumlah Halaman')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan jumlah halaman buku.')
                                    ->placeholder('Jumlah Halaman Buku'),

                                TextInput::make('negara')
                                    ->label('Negara')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan negara asal buku.')
                                    ->placeholder('Negara Buku'),
                                
                                TextInput::make('link_buku')
                                    ->label('Link Buku')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan link buku.')
                                    ->placeholder('Link Buku'),
                        ]),
                        Wizard\Step::make('Unggah Cover')
                                    ->schema([
                                        FileUpload::make('cover')
                                            ->label('Unggah Cover Buku')
                                            ->disk('public')
                                            ->directory('files/books/cover')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                            ->maxSize(2048)
                                            ->columnSpanFull()
                                            ->moveFiles()
                                            ->required()
                                            ->helperText('Unggah gambar cover (JPG, PNG - Maksimal 2MB)')
                                    ]),
                    ]),
        ])
        ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('judul')
                ->label('Judul')
                ->searchable()
                ->sortable(),
            TextColumn::make('penulis')
                ->label('Penulis')
                ->searchable()
                ->sortable(),
            TextColumn::make('penerbit')
                ->label('Penerbit')
                ->searchable()
                ->sortable(),
            TextColumn::make('isbn')
                ->label('ISBN')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus Buku')
                    ->icon('heroicon-o-trash') // Ikon trash
                    ->action(function ($record) {
                        // Periksa jika record memiliki relasi file
                        if ($record) {
                            // Hapu dari storage jika ada
                            if (Storage::exists($record->cover)) {
                                Storage::delete($record->cover);
                            }
                        }
                
                        // Hapus data Buku
                        $record->delete();
                    })
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBooks::route('/create'),
            'edit' => Pages\EditBooks::route('/{record}/edit'),
        ];
    }

    // Menggunakan successRedirectUrl di CreateAction
    public static function actions(): array
    {
        return [
            CreateAction::make()
                ->successRedirectUrl(route('books.index')), // Ganti dengan route yang sesuai
            EditAction::make()
                ->successRedirectUrl(route('books.index')), // Ganti dengan route yang sesuai
        ];
    }
}
