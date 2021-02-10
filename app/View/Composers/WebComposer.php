<?php

namespace App\View\Composers;

use App\Models\Menu\MenuGroup;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WebComposer
{
    public function __construct(private PostRepositoryInterface $postRepository){}

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // Settings Composer
        $settings =  cache()->remember('settings', 60 * 60 * 24, fn () =>
            DB::table('settings')
                ->where('language', app()->getLocale())
                ->whereIn('name', config('site.setting_names'))
                ->get()
                ->keyBy('name')
                ->pluck('value', 'name')
        );

        $view->with('settings', $settings);

        // Menus Composer
        $headerMenus = cache()->remember('header-menu', 60 * 60 * 6, fn () =>
            MenuGroup::itemsByLocale(config('site.header_menu'), 'item_id', 'parent_id', 'title', 'url')
        );
        $view->with('header_menus', buildTree($headerMenus, ['id' => 'item_id', 'parentId' => 'parent_id']));

        $quickLinks = cache()->remember('quick-links', 60 * 60 * 6, fn () =>
            MenuGroup::itemsByLocale(config('site.quick_links'), 'item_id', 'title', 'url')
        );
        $view->with('quick_links', $quickLinks);


        // Categories Composer
        $categories =  cache()->remember('home-categories', 60 * 60 * 6, fn () =>
            $this->postRepository->children(config('site.categories'), ['posts.id', 'title', 'url'])
        );
        $view->with('category_links', $categories);

        // Footer Composer
        $footer =  cache()->remember('footer', 60 * 60 * 6, fn () =>
            $this->postRepository->find(config('site.footer'), ['featured_image'])
        );
        $view->with('footer', $footer);
    }
}
