<?php

namespace App\Filament\Resources\RisetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static ?string $title = 'Dokumen Riset'; // Judul Relation Manager

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            FileUpload::make('path_file')
                ->label('Upload File')
                ->disk('public')
                ->directory('files/riset')
                ->columnSpanFull()
                ->required(),
            Hidden::make('tipe_file')
                ->label('Tipe File')
                ->default('riset')
                ->required(),
            PdfViewerField::make('path_file')
                 ->label('View the PDF')
                 ->minHeight('50svh')
                 ->columnSpanFull()
                 ->fileUrl(function ($record) {
                     if (!$record || !$record->path_file) {
                         return asset('files/default.pdf');
                     }
             
                     try {
                         return Storage::url($record->path_file);
                     } catch (\Exception $e) {
                         return asset('files/default.pdf');
                     }
                 })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Files')
            ->columns([
            Tables\Columns\TextColumn::make('risets.judul_riset')
                ->label('Nama File')
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
