<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();

        $repositories = [
            'Base',
            'User',
            'Store',
            'UserAddress',
            'Category',
            'CategoryChild',
            'Product',
            'ProductInformation',
            'ProductImage',
            'ProductFavorite',
            'Banner',
            'BannerStore',
            'Comment',
            'CommentImage',
            'MasterField',
            'ChildMasterField',
            'Cart',
            'Order',
            'OrderItem',
        ];

        foreach ($repositories as $repo) {
            $this->app->bind(
                'App\\Repositories\\' . $repo . '\\' . $repo . 'RepositoryInterface',
                'App\\Repositories\\' . $repo . '\\' . $repo . 'Repository'
            );
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
