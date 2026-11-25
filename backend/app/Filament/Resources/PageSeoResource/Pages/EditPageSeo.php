<?php

namespace App\Filament\Resources\PageSeoResource\Pages;

use App\Filament\Resources\PageSeoResource;
use Filament\Resources\Pages\EditRecord;

class EditPageSeo extends EditRecord
{
    protected static string $resource = PageSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Pengaturan SEO berhasil disimpan';
    }
}
