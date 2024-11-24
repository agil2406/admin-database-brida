<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EduwisataResource\Pages;
use App\Filament\Resources\EduwisataResource\RelationManagers;
use App\Models\Eduwisata;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EduwisataResource extends Resource
{
    protected static ?string $model = Eduwisata::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Eduwisata';
    
    protected static ?string $heading = 'Eduwisata';

    protected static ?string $title = 'Eduwisata';

    protected static ?string $label = 'Eduwisata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_lembaga')
                ->label('Nama Lembaga')
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    if (($get('slug_lembaga') ?? '') !== Str::slug($old)) {
                        return;
                    }
                    $set('slug_lembaga', Str::slug($state));
                })
                ->required()
                ->columnSpanFull(),
        
                Hidden::make('slug_lembaga')
                    ->label('Slug Lembaga'),

                Select::make('daerah_lembaga')
                    ->label('Daerah Lembaga')
                    ->options([
                        'kota_mataram' => 'Kota Mataram',
                        'kab_lombok_barat' => 'Kab. Lombok Barat',
                        'kab_lombok_timur' => 'Kab. Lombok Timur',
                        'kab_lombok_utara' => 'Kab. Lombok Utara',
                        'kab_lombok_tengah' => 'Kab. Lombok Tengah',
                        'kab_sumbawa' => 'Kab_Sumbawa',
                        'kab_sumbawa_barat' => 'Kab. Sumbawa Barat',
                        'kab_bima' => 'Kab. Bima',
                        'kota_bima' => 'Kota Bima',
                        'kab_dompu' => 'Kab. Dompu',
                        'aceh' => 'Aceh',
                        'bali' => 'Bali',
                        'banten' => 'Banten',
                        'bengkulu' => 'Bengkulu',
                        'yogyakarta' => 'Daerah Istimewa Yogyakarta',
                        'jakarta' => 'Daerah Khusus Ibukota Jakarta',
                        'gorontalo' => 'Gorontalo',
                        'jambi' => 'Jambi',
                        'jawa_barat' => 'Jawa Barat',
                        'jawa_tengah' => 'Jawa Tengah',
                        'jawa_timur' => 'Jawa Timur',
                        'kalimantan_barat' => 'Kalimantan Barat',
                        'kalimantan_selatan' => 'Kalimantan Selatan',
                        'kalimantan_tengah' => 'Kalimantan Tengah',
                        'kalimantan_timur' => 'Kalimantan Timur',
                        'kalimantan_utara' => 'Kalimantan Utara',
                        'bangka_belitung' => 'Kepulauan Bangka Belitung',
                        'riau' => 'Kepulauan Riau',
                        'lampung' => 'Lampung',
                        'maluku' => 'Maluku',
                        'maluku_utara' => 'Maluku Utara',
                        'ntt' => 'Nusa Tenggara Timur',
                        'papua' => 'Papua',
                        'papua_barat' => 'Papua Barat',
                        'papua_barat_daya' => 'Papua Barat Daya',
                        'papua_pegunungan' => 'Papua Pegunungan',
                        'papua_selatan' => 'Papua Selatan',
                        'papua_tengah' => 'Papua Tengah',
                        'riau' => 'Riau',
                        'sulawesi_barat' => 'Sulawesi Barat',
                        'sulawesi_selatan' => 'Sulawesi Selatan',
                        'sulawesi_tengah' => 'Sulawesi Tengah',
                        'sulawesi_tenggara' => 'Sulawesi Tenggara',
                        'sulawesi_utara' => 'Sulawesi Utara',
                        'sumatera_barat' => 'Sumatera Barat',
                        'sumatera_selatan' => 'Sumatera Selatan',
                        'sumatera_utara' => 'Sumatera Utara',
                    ])
                    ->default('kota mataram')
                    ->required()
                    ->helperText('Pilih Daerah Inovator'),
                
                DatePicker::make('jadwal_kunjungan')
                    ->label('Jadwal Kunjungan')
                    ->required()
                    ->helperText('Pilih Tanggal Kunjungan'),

                Select::make('asal_lembaga')
                    ->label('Asal Lembaga')  // Label untuk input form
                    ->options([
                        'paud' => 'PAUD (Pendidikan Anak Usia Dini)',
                        'tk' => 'TK (Taman Kanak-Kanak)',
                        'sd' => 'SD (Sekolah Dasar)',
                        'smp' => 'SMP (Sekolah Menengah Pertama)',
                        'mts' => 'MTS (Madrasah Tsanawiyah)',
                        'sma' => 'SMA (Sekolah Menengah Atas)',
                        'ma' => 'MA (Madrasah Aliyah)',
                        'smk' => 'SMK (Sekolah Menengah Kejuruan)',
                        'slb' => 'SLB (Sekolah Luar Biasa)',
                        'perguruan_tinggi' => 'Perguruan Tinggi (Universitas/Politeknik)',
                        'instansi' => 'Instansi (Lembaga Pemerintahan atau Swasta)',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->helperText('Pilih asal lembaga')
                    ->placeholder('Pilih asal lembaga')
                    ->default('paud') // Default value bisa diubah jika perlu
                    ->columnSpanFull(),  // Agar seleksi penuh

                TextInput::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->numeric()
                    ->minValue(1) // Set the minimum value for jumlah peserta
                    ->required()
                    ->helperText('Masukkan Jumlah Peserta')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lembaga')
                ->label('Nama Lembaga')
                ->searchable()
                ->sortable()
                ->description(fn (Eduwisata $record): string => 
                    ' Jumlah Peserta: ' . $record->jumlah_peserta, position: 'below'),

                TextColumn::make('jadwal_kunjungan')
                ->label('Jadwal Kunjungan')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->isoFormat('DD MMMM YYYY'))
                    
            ])
            ->filters([
                SelectFilter::make('asal_lembaga')
                    ->options([
                        'paud' => 'PAUD (Pendidikan Anak Usia Dini)',
                        'tk' => 'TK (Taman Kanak-Kanak)',
                        'sd' => 'SD (Sekolah Dasar)',
                        'smp' => 'SMP (Sekolah Menengah Pertama)',
                        'mts' => 'MTS (Madrasah Tsanawiyah)',
                        'sma' => 'SMA (Sekolah Menengah Atas)',
                        'ma' => 'MA (Madrasah Aliyah)',
                        'smk' => 'SMK (Sekolah Menengah Kejuruan)',
                        'slb' => 'SLB (Sekolah Luar Biasa)',
                        'perguruan_tinggi' => 'Perguruan Tinggi (Universitas/Politeknik)',
                        'instansi' => 'Instansi (Lembaga Pemerintahan atau Swasta)',
                        'lainnya' => 'Lainnya',
                    ]),
                Filter::make('jadwal_kunjungan')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('created_until')
                            ->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('jadwal_kunjungan', '>=', Carbon::parse($date)->toDateString()),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('jadwal_kunjungan', '<=', Carbon::parse($date)->toDateString()),
                            );
                    }),

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
            'index' => Pages\ListEduwisatas::route('/'),
            'create' => Pages\CreateEduwisata::route('/create'),
            'edit' => Pages\EditEduwisata::route('/{record}/edit'),
        ];
    }
}
