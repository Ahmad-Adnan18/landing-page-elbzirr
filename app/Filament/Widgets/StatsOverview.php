<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StoreSetting;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        
        $isStoreOpen = StoreSetting::where('key', 'is_store_open')->value('value') === 'true';
        $storeStatus = $isStoreOpen ? 'BUKA' : 'TUTUP';
        $storeColor = $isStoreOpen ? 'success' : 'danger';
        $storeIcon = $isStoreOpen ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle';

        return [
            Stat::make('Total Produk', $totalProducts)
                ->description('Semua produk terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            
            Stat::make('Produk Aktif', $activeProducts)
                ->description('Produk yang tampil di web')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Status Toko', $storeStatus)
                ->description($isStoreOpen ? 'Toko menerima pesanan' : 'Mode Pre-Order')
                ->descriptionIcon($storeIcon)
                ->color($storeColor),
        ];
    }
}
