<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromView, WithTitle, ShouldAutoSize
{
    public function view(): View
    {
        // Prepare user query
        $query = User::query();
        // Chech if user has super-admin role
        if( ! \Auth::user()->hasRole('super-admin')) {
          // Exclude super-user User for non super-admin user
          $query->whereDoesntHave('roles', function($r) {
            return $r->where('name', 'super-admin');
          });
        }
        // Get data based on user query
        $items = $query->with(['roles', 'branches', 'lastLog'])
                       ->orderBy('name')
                       ->get();
        // return view
        return view('exports.users', compact(['items']));
    }

    public function title(): string
    {
        return "Users";
    }
}
