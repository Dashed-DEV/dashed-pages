<?php

namespace Qubiqx\QcommercePages\Classes;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Qubiqx\QcommerceCore\Classes\Sites;
use Qubiqx\QcommercePages\Models\Page;

class PageRouteHandler
{
    public static function handle($parameters = [])
    {
        $slug = $parameters['slug'] ?? '';
        if ($slug) {
            $slugParts = explode('/', $slug);
            $parentPageId = null;
            foreach ($slugParts as $slugPart) {
                $page = Page::publicShowable()->where('slug->' . App::getLocale(), $slugPart)->where('parent_page_id', $parentPageId)->where('is_home', 0)->first();
                $parentPageId = $page?->id;
                if(!$page){
                    return 'pageNotFound';
                }
            }
        } else {
            $page = Page::publicShowable()->where('is_home', 1)->first();
        }

        if ($page) {
            if (View::exists('qcommerce.pages.show')) {
                seo()->metaData('metaTitle', $page->meta_title ?: $page->name);
                seo()->metaData('metaDescription', $page->meta_description);
                if ($page->meta_image) {
                    seo()->metaData('metaImage', $page->meta_image);
                }

                $correctLocale = App::getLocale();
                $alternateUrls = [];
                foreach (Sites::getLocales() as $locale) {
                    if ($locale['id'] != $correctLocale) {
                        LaravelLocalization::setLocale($locale['id']);
                        App::setLocale($locale['id']);
                        $alternateUrls[$locale['id']] = $page->getUrl();
                    }
                }
                LaravelLocalization::setLocale($correctLocale);
                App::setLocale($correctLocale);
                seo()->metaData('alternateUrls', $alternateUrls);

                View::share('page', $page);

                return view('qcommerce.pages.show');
            } else {
                return 'pageNotFound';
            }
        }
    }
}
