<?php

namespace App\Helpers;

use App\Models\Menu;

class MenuHelper
{
    public static $menuPathPrefix = '/administrator';

    /**
     * Format menu path.
     *
     * @param   string  $path
     * @param   string|int  $parentId
     *
     * @return  string|JsonResponse
     */
    public static function setMenuPath(string $path, $parentId)
    {
        if (is_numeric($parentId)) {
            $parentMenu = Menu::select('id', 'path')->whereId($parentId)->first();

            if (!$parentMenu) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Parent menu is invalid.',
                ], 404);
            }

            $path = $parentMenu->path . $path;
        } else {
            $path = self::$menuPathPrefix . $path;
        }

        return $path;
    }
}
