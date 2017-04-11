<?php 
if (!function_exists('sidebar_open')) {
    function sidebar_open($routes = []) {
        $currRoute = Route::currentRouteName();
        $open = false;
        foreach ($routes as $route) {
        	if (str_contains($route, '*')) {
        		if (str_contains($currRoute, substr($route, '-1'))) {
        			$open = true;
        			break;
        		}
        	} else {
        		if ($currRoute === $route) {
        			$open = true;
        			break;
        		}
        	}
        }
        return $open ? 'active' : '';
    }
}

if (!function_exists('treeview_menu_open')) {
	function treeview_menu_open($routes = []) {
        $currRoute = Route::currentRouteName();
        $open = false;
        foreach ($routes as $route) {
        	if (str_contains($route, '*')) {
        		if (str_contains($currRoute, substr($route, '-1'))) {
        			$open = true;
        			break;
        		}
        	} else {
        		if ($currRoute === $route) {
        			$open = true;
        			break;
        		}
        	}
        }
        return $open ? 'class="treeview-menu menu-open" style="display:block"' : "class='treeview-menu'";
    }
}