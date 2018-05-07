<?php

namespace App\Widgets;

use Radkod\Posts\Models\Posts;

/**
 *  |--------------------------------------------------------------------------
 *  |   You can use the below snippets to override the defaults.
 *  |====== Remove this doc block if you don't need it ========
 *  |--------------------------------------------------------------------------
 *  | public $controller = App/classPath/MyWidget::class; (optional)
 *  | public $presenter = App/classPath/WidgetPresenter::class; (optional)
 *  | public $minifyOutput = true; (use it to override the default value in config file)
 *  | public $cacheLifeTime = 0;   (use it to override the default value in config file)
 *  | public $contextAs = '$data'; (Default is : '$data')
 *  | public $template; (optional) (By default it looks for the view file generated by artisan)
 *  | public $cacheTags = []; (optional) (Available for all cache driver)
 *  |--------------------------------------------------------------------------
 */
class TrendVideos
{
   /**
    * You may call this widget in any blade files like this :
    *
    * @widget('TrendVideos', ['a' => 'someVal', 'b' => 'foo'])
    */
	public function data()
	{
        $date = date('Y-m-d H:i:s');
        $posts = Posts::orderBy("id","DESC")
            ->where('created_at', '<=', $date)
            ->where('type', 1)
            ->limit(3)->get();
        return compact("posts");
	}


   /**
    * By default the cacheKey of your widget output does not
    * depend on any Global variables like $_GET. So if the final
    * widget output depend on them you should return them here.
    * otherwise you get undesired results out of the cache store.
    */
	public function extraCacheKeyDependency()
	{
        return [];
	}

}
