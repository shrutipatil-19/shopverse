<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Textarea, Toggle, Select};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, ToggleColumn};
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Pages\CreateRecord;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                TextInput::make('sku'),
                Textarea::make('short_description'),
                Textarea::make('description'),
                TextInput::make('price')->numeric()->required(),
                TextInput::make('discount_price')->numeric(),
                TextInput::make('currency')->default('INR'),
                TextInput::make('quantity')->numeric()->required(),
                Select::make('stock_status')->options([
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out of Stock',
                    'pre_order' => 'Pre Order',
                ]),
                Toggle::make('visibility')->label('Visible on site'),
                Toggle::make('is_featured')->label('Featured'),
                Select::make('status')->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'draft' => 'Draft',
                ]),
                FileUpload::make('image')
                    ->image()
                    ->directory('products') // Saves under storage/app/public/products
                    ->disk('public') // Important: use public disk
                    ->imageEditor()
                    ->nullable(),
                Select::make('category_id')->relationship('category', 'name'),
                Select::make('brand_id')->relationship('brand', 'name'),
                Select::make('user_id')->relationship('user', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->limit(20),
                TextColumn::make('sku')->sortable(),
                TextColumn::make('price')->money('INR', true),
                TextColumn::make('discount_price')->money('INR', true),
                TextColumn::make('currency'),
                TextColumn::make('quantity')->sortable(),
                BadgeColumn::make('stock_status')
                    ->colors([
                        'success' => 'in_stock',
                        'warning' => 'pre_order',
                        'danger' => 'out_of_stock',
                    ]),
                ToggleColumn::make('visibility')->label('Visible'),
                ToggleColumn::make('is_featured')->label('Featured'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'gray' => 'draft',
                    ]),
                ImageColumn::make('image')
                    ->disk('s3'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('brand.name')->label('Brand'),
                TextColumn::make('user.name')->label('Added By'),
                TextColumn::make('created_at')->dateTime('d M Y'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
