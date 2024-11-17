<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InovasiResource\Pages;
use App\Filament\Resources\InovasiResource\RelationManagers\FileRelationManager;
use App\Models\Inovasi;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\Tipe;
use Closure;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Illuminate\Support\Str;

class InovasiResource extends Resource
{
    protected static ?string $model = Inovasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'Inovasi';
    
    protected static ?string $heading = 'Inovasi';

    protected static ?string $title = 'Inovasi';

    protected static ?string $label = 'Inovasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                    ->label('Form Inovasi')
                    ->steps([
                        // Step 1
                        Wizard\Step::make('Informasi Inovasi')
                            ->schema([
                                TextInput::make('nama_inovasi')
                                    ->label('Nama Inovasi')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText('Masukkan nama inovasi dengan lengkap.')
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        // Jika nama_inovasi tidak berubah, tidak perlu melakukan apa-apa
                                        if (($get('slug_inovasi') ?? '') !== Str::slug($old)) {
                                            return;
                                        }
                                
                                        // Membuat slug dari nama inovasi yang diinputkan
                                        $slug = Str::slug($state);
                                
                                        // Menambahkan angka jika slug sudah ada di database
                                        $existingCount = Inovasi::where('slug_inovasi', 'like', $slug . '%')->count();
                                        
                                        if ($existingCount > 0) {
                                            $slug .= '-' . ($existingCount + 1); // Menambahkan angka agar slug unik
                                        }

                                        $set('wizard.4.nama_file', $slug);
                                        // Menyimpan slug yang telah diubah ke field 'slug_inovasi'
                                        $set('slug_inovasi', $slug);
                                    })
                                    // ->required()
                                    ->columnSpanFull(),
                            
                            Hidden::make('slug_inovasi')
                                    ->label('Slug Inovasi'),

                                Select::make('kategori_id')
                                    ->label('Kategori')
                                    ->relationship('kategori', 'nama_kategori')
                                    ->searchable()
                                    ->required()
                                    ->extraAttributes(['class' => 'w-full'])
                                    ->helperText(new HtmlString('<a href="'.route('filament.admin.resources.kategoris.create').'">Tambah kategori ?</a>'))
                                    ->options(function () {
                                        return Kategori::where('tipe_kategori', 'inovasi')->pluck('nama_kategori', 'id');
                                    }),

                                Textarea::make('fungsi_inovasi')
                                    ->label('Fungsi / Manfaat Inovasi')
                                    ->placeholder('Fungsi / Manfaat Inovasi')
                                    ->helperText('Masukkan fungsi atau manfaat inovasi dengan singkat.')
                                    ->rows(3)
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('tahun_inovasi')
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
                                            $set('tahun_inovasi', null); // Reset jika tahun di luar rentang
                                            Notification::make()
                                                ->title('Tahun tidak valid.')
                                                ->body('Masukkan tahun antara 2010 dan tahun saat ini.')
                                                ->danger()
                                                ->send();
                                        }
                                    }),

                                Select::make('sertifikat_inovasi')
                                    ->label('Apakah Inovasi Sudah Memiliki Sertifikat ?')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                    ])
                                    ->required()
                                    ->default('tidak'),

                                Select::make('desiminasi_inovasi')
                                    ->label('Apakah Inovasi Sudah Dilakukan Desiminasi ?')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                    ])
                                    ->required()
                                    ->default('tidak')
                                    ->helperText('Diseminasi adalah proses penyebaran ide, gagasan, inovasi, atau hasil penelitian kepada khalayak yang lebih luas'),
                            ]),

                        // Step 2
                        Wizard\Step::make('Informasi Inovator')
                            ->schema([
                                TextInput::make('nama_inovator')
                                    ->label('Nama Inovator')
                                    ->required()
                                    ->helperText('Masukkan nama lengkap inovator'),

                                Textarea::make('alamat_inovator')
                                    ->label('Alamat Inovator')
                                    ->required()
                                    ->placeholder('Masukkan alamat lengkap')
                                    ->rows(3)
                                    ->helperText('Masukkan alamat lengkap inovator'),

                                TextInput::make('kontak_inovator')
                                    ->label('Kontak Inovator')
                                    ->required()
                                    ->placeholder('+62xxxxxxxxxxx')
                                    ->rules(['regex:/^\+62\d{9,13}$/'])
                                    ->maxLength(16)
                                    ->helperText('Masukkan no kontak dengan awalan +62'),

                                Select::make('daerah_inovator')
                                    ->label('Kota / Kabupaten Inovator')
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
                                    ->helperText('Pilih Daerah Inovator'),
                            ]),

                        // Step 3
                        Wizard\Step::make('Spesifikasi Inovasi')
                            ->schema([
                                Select::make('instansi_id')
                                    ->label('Instansi / Lembaga')
                                    ->required()
                                    ->relationship('instansi', 'nama_instansi')
                                    ->searchable()
                                    ->options(function () {
                                        return Instansi::pluck('nama_instansi', 'id');
                                    })
                                    ->helperText(new HtmlString('<a href="'.route('filament.admin.resources.instansis.create').'">Tambah instansi ?</a>')),

                                Select::make('tipe_id')
                                    ->label('Program Inovasi')
                                    ->required()
                                    ->relationship('tipe', 'nama_tipe')
                                    ->searchable()
                                    ->options(function () {
                                        return Tipe::pluck('nama_tipe', 'id');
                                    })
                                    ->helperText(new HtmlString('<a href="'.route('filament.admin.resources.jenis-programs.create').'">Tambah program ?</a>')),

                                Select::make('status_inovasi')
                                    ->label('Status Inovasi')
                                    ->required()
                                    ->options([
                                        'digital' => 'Digital',
                                        'non digital' => 'Non Digital',
                                    ])
                                    ->default('non digital')
                                    ->helperText('Pilih Status Inovasi'),

                                RichEditor::make('spesifikasi_inovasi')
                                    ->label('Spesifikasi Inovasi')
                                    ->required()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ])
                                    ->columnSpanFull()
                                    ->helperText('Masukkan spesifikasi inovasi dengan lengkap'),
                            ]),

                        // Step 5
                        Wizard\Step::make('Unggah Dokumen')
                            ->schema([
                                Fieldset::make('Dokumen Pendukung')
                                ->relationship('file')
                                ->schema([
                                    FileUpload::make('path_file')
                                        ->label('Unggah File')
                                        ->disk('public')
                                        ->directory('files/inovasi')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->maxSize(2048)
                                        ->columnSpanFull()
                                        ->moveFiles()
                                        ->required()
                                        ->helperText('Unggah file atau dokumen PDF (Maksimal 2MB)')
                                        ->getUploadedFileNameForStorageUsing(function (UploadedFile $file)  {
                                            // Membuat nama file berdasarkan slug_inovasi dan menambahkan ekstensi file yang sesuai
                                            return 'inovasis-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                                        })
                                        ->columnSpanFull(),

                            
                                    Hidden::make('tipe_file')
                                        ->default('inovasi'),
                            
                                        // PdfViewerField::make('path_file')
                                        // ->label('View the PDF')
                                        // ->minHeight('40svh')
                                        // ->columnSpanFull()
                                        // ->fileUrl(function ($record) {
                                        //     if (!$record || !$record->path_file) {
                                        //         return asset('files/default.pdf');
                                        //     }
                                    
                                        //     try {
                                        //         return Storage::url($record->path_file);
                                        //     } catch (\Exception $e) {
                                        //         Log::error('Error getting file URL: ' . $e->getMessage());
                                        //         return asset('files/default.pdf');
                                        //     }
                                        // })
                                        
                                        // ->fileUrl(function ($record) {
                                        //     // Cek jika parameter 'record' ada di rute dan file tersedia
                                        //     if ($record && $record->file && $record->file->isNotEmpty()) {
                                        //         // Mengembalikan URL file dari storage
                                        //         return Storage::url($record->file->path_file);
                                        //     }
                                    
                                        //     // Fallback jika tidak ada file
                                        //     return ''; // atau fallback URL lainnya
                                        // })
                                        // Sembunyikan jika tidak ada file atau record yang ditemukan
                                        // ->hidden(fn (Get $get, $record) => !$record || !$record->file || $record->file->isEmpty())

                                    
                                ])
                               
                            ])
                            
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nama_inovasi')
                ->label('Nama Inovasi')
                ->searchable()
                ->sortable(),
                // ->description(fn (Inovasi $record): string => Str::words($record->fungsi_inovasi,5), position: 'below'),
            TextColumn::make('instansi.nama_instansi')
                ->label('Nama Instansi')
                ->searchable()
                ->sortable(),
            TextColumn::make('tipe.nama_tipe')
                ->label('Program Inovasi')
                ->searchable()
                ->sortable(),
            TextColumn::make('tahun_inovasi')
                ->label('Tahun Inovasi')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('instansi_id')
                    ->label('Instansi')
                    ->options(fn (): array => Instansi::query()->pluck('nama_instansi', 'id')->all()),
                SelectFilter::make('tipe_id')
                    ->label('Jenis Program')
                    ->options(fn (): array => Tipe::query()->pluck('nama_tipe', 'id')->all()),
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->options(fn (): array => Kategori::query()->where('tipe_kategori','inovasi')->pluck('nama_kategori', 'id')->all()),
                SelectFilter::make('desiminasi_inovasi')
                    ->options([
                        'ya' => 'Ya',
                        'tidak' => 'Tidak',
                    ]),
                SelectFilter::make('sertifikat_inovasi')
                    ->options([
                        'ya' => 'Ya',
                        'tidak' => 'Tidak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus Inovasi')
                    ->icon('heroicon-o-trash') // Ikon trash
                    ->action(function ($record) {
                        // Periksa jika record memiliki relasi file
                        if ($record->file) {
                            // Hapus file dari storage jika ada
                            if (Storage::exists($record->file->path_file)) {
                                Storage::delete($record->file->path_file);
                            }
                
                            // Hapus data file yang terkait di tabel files
                            $record->file->delete();
                        }
                
                        // Hapus data inovasi
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
            FileRelationManager::class, // Tambahkan Relation Manager di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInovasis::route('/'),
            'create' => Pages\CreateInovasi::route('/create'),
            'view' => Pages\ViewInovasi::route('/{record}'),
            'edit' => Pages\EditInovasi::route('/{record}/edit'),
        ];
    }

    // Menggunakan successRedirectUrl di CreateAction
    public static function actions(): array
    {
        return [
            CreateAction::make()
                ->successRedirectUrl(route('inovasis.index')), // Ganti dengan route yang sesuai
            EditAction::make()
                ->successRedirectUrl(route('inovasis.index')), // Ganti dengan route yang sesuai
        ];
    }
}



