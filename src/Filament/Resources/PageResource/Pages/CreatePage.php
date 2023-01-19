<?php

namespace Qubiqx\QcommercePages\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Support\Str;
use Qubiqx\QcommerceCore\Classes\Sites;
use Qubiqx\QcommercePages\Filament\Resources\PageResource;
use Qubiqx\QcommercePages\Models\Page;

class CreatePage extends CreateRecord
{
    use Translatable;

    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        while (Page::where('slug->' . $this->activeFormLocale, $data['slug'])->count()) {
            $data['slug'] .= Str::random(1);
        }

        $data['site_id'] = $data['site_id'] ?? Sites::getFirstSite()['id'];
//        $content = $data['content'];
//        $data['content'] = null;
//        $data['content'][$this->activeFormLocale] = $content;

        return $data;
    }
}
