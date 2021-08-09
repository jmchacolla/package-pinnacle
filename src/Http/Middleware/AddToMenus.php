<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Middleware;

use Closure;
use Lavary\Menu\Facade as Menu;


class AddToMenus
{

    public function handle($request, Closure $next)
    {

        // Add a menu option to the top to point to our page

        $menu = Menu::get('topnav');
        $menu->add(__('Request Report'), ['route' => 'package.pinnacle.tab.pinnacle-report']);

        // Add a option in the admin menu to point to our page
        $menu = Menu::get('sidebar_admin')->first();
        return $next($request);
    }

}
