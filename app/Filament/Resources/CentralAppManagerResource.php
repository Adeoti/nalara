<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\CentralAppManager;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CentralAppManagerResource\Pages;
use App\Filament\Resources\CentralAppManagerResource\RelationManagers;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class CentralAppManagerResource extends Resource
{
    protected static ?string $model = CentralAppManager::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'App Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Section::make()->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('link')
                
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('group')
                ->required()
                ->maxLength(255),
            FileUpload::make('logo')
                ->required()
                ->imageEditor()
                ->uploadingMessage('Uploading logo...')
                ->image(),
            Hidden::make('user_id')->default(Auth::user()->id),
            Forms\Components\Toggle::make('is_active')
                ->required(),
               ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->copyable()
                    ->limit(5)
                    ->words(6, '...')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                ImageColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCentralAppManagers::route('/'),
            'create' => Pages\CreateCentralAppManager::route('/create'),
            'edit' => Pages\EditCentralAppManager::route('/{record}/edit'),
        ];
    }
}
