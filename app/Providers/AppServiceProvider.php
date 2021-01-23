<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        header_remove("X-Powered-By");
        session_name('CODETHEREALSESSID');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register the Starting Tag
        Blade::directive('spaceless', function() {
            return '<?php ob_start() ?>';
        });

        //Register the Ending Tag
        Blade::directive('endspaceless', function() {
            return "<?php echo preg_replace('/>\\s+</', '><', ob_get_clean()); ?>";
        });
    }
}
