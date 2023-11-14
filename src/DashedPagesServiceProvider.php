<?php

namespace Dashed\DashedPages;

use Dashed\DashedPages\Models\Page;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Dashed\DashedPages\Filament\Resources\PageResource;

class DashedPagesServiceProvider extends PluginServiceProvider
{
    public static string $name = 'dashed-pages';

    public function configurePackage(Package $package): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        cms()->model('Page', Page::class);

        cms()->builder(
            'routeModels',
            array_merge(cms()->builder('routeModels'), [
                'page' => [
                    'name' => 'Pagina',
                    'pluralName' => 'Pagina\'s',
                    'class' => Page::class,
                    'nameField' => 'name',
                ],
            ])
        );

        $package
            ->name('dashed-pages');
    }

    protected function getResources(): array
    {
        return array_merge(parent::getResources(), [
            PageResource::class,
        ]);
    }
}
