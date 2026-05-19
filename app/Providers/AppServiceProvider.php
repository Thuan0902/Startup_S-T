<?php

namespace App\Providers;

use App\Models\PageContent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $pageKey = PageContent::pageKeyFromRoute();
            $pageConfig = $pageKey ? config("page_content.pages.{$pageKey}") : null;

            $siteConfig = config('page_content.pages.site', []);

            $view->with([
                'pageEditorKey' => $pageKey,
                'pageEditorConfig' => $pageConfig,
                'pageEditorFields' => $pageConfig['fields'] ?? [],
                'pageContent' => PageContent::valuesForPage($pageKey),
                'siteEditorConfig' => $siteConfig,
                'siteEditorFields' => $siteConfig['fields'] ?? [],
                'sitePageContent' => PageContent::valuesForPage('site'),
            ]);
        });
    }
}
