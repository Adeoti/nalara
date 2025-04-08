<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\NewsCategory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentNewsWidget extends BaseWidget
{


    protected array|string|int $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
                News::query()->limit(8)
            )
            ->columns([
                // ...
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('title'),
                TextColumn::make('newsCategory.name'),
                ImageColumn::make('image')
            ]);
    }
}
