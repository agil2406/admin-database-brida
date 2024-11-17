<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RisetResource\Pages;
use App\Filament\Resources\RisetResource\RelationManagers;
use App\Filament\Resources\RisetResource\RelationManagers\FilesRelationManager;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\Riset;
use App\Models\Tipe;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class RisetResource extends Resource
{
    protected static ?string $model = Riset::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Riset';
    
    protected static ?string $heading = 'Riset';

    protected static ?string $title = 'Riset';

    protected static ?string $label = 'Riset';

    public static function form(Form $form): Form
    {
        $testing = $form->getRecord();
        return $form
            ->schema([
                Wizard::make()
                    ->label('Form Inovasi')
                    ->steps([
                        // Step 1
                        Wizard\Step::make('Informasi Riset')
                            ->schema([
                                TextInput::make('judul_riset')
                                    ->label('Judul Riset')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan Judul Riset dengan lengkap.')
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        // Jika judul_riset tidak berubah, tidak perlu melakukan apa-apa
                                        if (($get('slug_riset') ?? '') !== Str::slug($old)) {
                                            return;
                                        }
                                
                                        // Membuat slug dari judul Riset yang diinputkan
                                        $slug = Str::slug($state);
                                
                                        // Menambahkan angka jika slug sudah ada di database
                                        $existingCount = Riset::where('slug_riset', 'like', $slug . '%')->count();
                                        
                                        if ($existingCount > 0) {
                                            $slug .= '-' . ($existingCount + 1); // Menambahkan angka agar slug unik
                                        }

                                        // Menyimpan slug yang telah diubah ke field 'slug_riset'
                                        $set('slug_riset', $slug);
                                    })
                                    // ->required()
                                    ->columnSpanFull(),
                            
                                Hidden::make('slug_riset')
                                    ->label('Slug Riset'),

                                Select::make('kategori_id')
                                    ->label('Kategori')
                                    ->relationship('kategori', 'nama_kategori')
                                    ->searchable()
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText(new HtmlString('<a href="'.route('filament.admin.resources.kategoris.create').'">Tambah kategori ?</a>'))
                                    ->options(function () {
                                        return Kategori::where('tipe_kategori', 'riset')->pluck('nama_kategori', 'id');
                                    }),

                                RichEditor::make('deskripsi_riset')
                                    ->label('Deskripsi Riset')
                                    ->required()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ])
                                    ->columnSpanFull()
                                    ->helperText('Masukkan deskripsi riset dengan lengkap'),

                                TextInput::make('tahun_riset')
                                    ->label('Tahun')
                                    ->placeholder('YYYY')
                                    ->numeric()
                                    ->required()
                                    ->minLength(4)
                                    ->maxLength(4)
                                    ->rules('digits:4')
                                    ->helperText('Masukkan tahun dengan format 4 digit, misal: 2024.')
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Validasi untuk memastikan tahun antara 2010 dan tahun saat ini
                                        if ($state && ($state < 2010 || $state > intval(date('Y')))) {
                                            $set('tahun_riset', null); // Reset jika tahun di luar rentang
                                            Notification::make()
                                                ->title('Tahun tidak valid.')
                                                ->body('Masukkan tahun antara 2010 dan tahun saat ini.')
                                                ->danger()
                                                ->send();
                                        }
                                    }),
                                Select::make('instansi_id')
                                    ->label('Instansi / Lembaga')
                                    ->required()
                                    ->relationship('instansi', 'nama_instansi')
                                    ->searchable()
                                    ->options(function () {
                                        return Instansi::pluck('nama_instansi', 'id');
                                    })
                                    ->helperText(new HtmlString('<a href="'.route('filament.admin.resources.instansis.create').'">Tambah instansi ?</a>')),

                                Select::make('desiminasi_riset')
                                    ->label('Apakah Riset Sudah Dilakukan Desiminasi ?')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                    ])
                                    ->required()
                                    ->default('tidak')
                                    ->helperText('Diseminasi adalah proses penyebaran ide, gagasan, riset, atau hasil penelitian kepada khalayak yang lebih luas'),
                            ]),

                        // Step 2
                        Wizard\Step::make('Informasi Peneliti')
                            ->schema([
                                TextInput::make('nama_peneliti')
                                    ->label('Nama Peneliti')
                                    ->required()
                                    ->helperText('Masukkan nama lengkap peneliti'),

                                Textarea::make('alamat_peneliti')
                                    ->label('Alamat Peneliti')
                                    ->required()
                                    ->placeholder('Masukkan alamat lengkap')
                                    ->rows(3)
                                    ->helperText('Masukkan alamat lengkap peneliti'),

                                TextInput::make('kontak_peneliti')
                                    ->label('Kontak Peneliti')
                                    ->required()
                                    ->placeholder('+62xxxxxxxxxxx')
                                    ->rules(['regex:/^\+62\d{9,13}$/'])
                                    ->maxLength(16)
                                    ->helperText('Masukkan no kontak dengan awalan +62'),

                                Select::make('daerah_peneliti')
                                    ->label('Kota / Kabupaten Peneliti')
                                    ->options([
                                        'kota mataram' => 'Kota Mataram',
                                        'kab. lombok barat' => 'Kab. Lombok Barat',
                                        'kab. lombok timur' => 'Kab. Lombok Timur',
                                        'kab. lombok utara' => 'Kab. Lombok Utara',
                                        'kab. lombok tengah' => 'Kab. Lombok Tengah',
                                        'kab. sumbawa' => 'Kab. Sumbawa',
                                        'kab. sumbawa Barat' => 'Kab. Sumbawa Barat',
                                        'kab. bima' => 'Kab. Bima',
                                        'kota bima' => 'Kota Bima',
                                        'kab. dompu' => 'Kab. Dompu',
                                    ])
                                    ->default('kota mataram')
                                    ->required()
                                    ->helperText('Pilih Daerah peneliti'),
                            ]),

                        // Step 3
                        Wizard\Step::make('Unggah Dokumen')
                            ->schema([
                                Repeater::make('Dokumen Pendukung')
                                ->relationship('files') // Menghubungkan ke relasi files
                                ->schema([
                                    TextInput::make('nama_file')
                                        ->label('Nama File')
                                        ->required()
                                        ->helperText('Masukkan nama file dengan benar')
                                        ->afterStateUpdated(function ($state, $set) {
                                            $set('nama_file', $state);  // Set the updated state back to 'nama_file'
                                        }),
                                    FileUpload::make('path_file')
                                        ->label('Unggah File')
                                        ->disk('public')
                                        ->directory('files/riset')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->maxSize(2048)
                                        ->moveFiles()
                                        ->required()
                                        ->helperText('Unggah file atau dokumen PDF (Maksimal 2MB)')
                                        ->getUploadedFileNameForStorageUsing(function (UploadedFile $file)  {
                                            // Membuat nama file berdasarkan slug_inovasi dan menambahkan ekstensi file yang sesuai
                                            return 'riset-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                                        }),

                                    Hidden::make('tipe_file')
                                        ->default('riset'),
                                    
                                ])
                                ->columns(1) // Elemen dalam satu kolom
                                ->label('Dokumen Pendukung'),
                            ])
                            
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('judul_riset')
                ->label('Judul Riset')
                ->searchable()
                ->sortable(),
            TextColumn::make('instansi.nama_instansi')
                ->label('Nama Instansi')
                ->searchable()
                ->sortable(),
            TextColumn::make('kategori.nama_kategori')
                ->label('Nama Kategori')
                ->searchable()
                ->sortable(),
            TextColumn::make('tahun_riset')
                ->label('Tahun Riset')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
            SelectFilter::make('instansi_id')
                ->label('Instansi')
                ->options(fn (): array => Instansi::query()->pluck('nama_instansi', 'id')->all()),
            SelectFilter::make('desiminasi_riset')
                ->options([
                    'ya' => 'Ya',
                    'tidak' => 'Tidak',
                ]),
            SelectFilter::make('kategori_id')
                ->label('Kategori')
                ->options(fn (): array => Kategori::query()->where('tipe_kategori','riset')->pluck('nama_kategori', 'id')->all())
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus Riset')
                    ->icon('heroicon-o-trash') // Ikon trash
                    ->action(function ($record) {
                        // Periksa jika record memiliki relasi files (menggunakan hasMany atau relasi terkait)
                        if ($record->files) {
                            foreach ($record->files as $file) {
                                // Hapus file dari storage jika ada
                                if (Storage::exists($file->path_file)) {
                                    Storage::delete($file->path_file);
                                }
                
                                // Hapus data file yang terkait di tabel files
                                $file->delete();
                            }
                        }
                
                        // Hapus data Riset
                        $record->delete();
                    }),
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
            FilesRelationManager::class, // Tambahkan Relation Manager di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisets::route('/'),
            'create' => Pages\CreateRiset::route('/create'),
            'view' => Pages\ViewRiset::route('/{record}'),
            'edit' => Pages\EditRiset::route('/{record}/edit'),
        ];
    }
}









