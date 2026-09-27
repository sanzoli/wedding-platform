<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guest;

class GuestCategoryController extends Controller
{
    public function add(Guest $guest, Category $category)
    {
        $guest->categories()->syncWithoutDetaching($category);

        return back();
    }

    public function remove(Guest $guest, Category $category)
    {
        $guest->categories()->detach($category);

        return back();
    }
}
