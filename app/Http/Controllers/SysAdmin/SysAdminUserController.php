<?php

declare(strict_types=1);

namespace App\Http\Controllers\SysAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SysAdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'admin')
            ->orderByDesc('created_at');

        $search = $request->query('q');
        if (is_string($search) && $search !== '') {
            $term = '%' . trim($search) . '%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        $users = $query->paginate(25)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filterQ' => is_string($search) ? $search : '',
        ]);
    }
}
