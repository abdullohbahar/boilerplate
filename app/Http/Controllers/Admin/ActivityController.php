<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $query = Activity::with('causer')
            ->orderBy('created_at', $direction);

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHasMorph('causer', '*', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $activities = $query->paginate(25)->withQueryString();

        return view('admin.activity.index', compact('activities'));
    }
}
