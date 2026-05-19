<?php

namespace App\Http\Controllers;

use App\Models\Occasion;
use Illuminate\Http\Request;

class OccasionController extends Controller
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $status = (string) request('status', '');

        $occasions = Occasion::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.occasion', compact('occasions', 'search', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['status'] = $request->boolean('status');

        Occasion::create($validated);

        return redirect()->route('occasion')->with('success', 'Occasion created successfully.');
    }

    public function update(Request $request, Occasion $occasion)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['status'] = $request->boolean('status');

        $occasion->update($validated);

        return redirect()->route('occasion')->with('success', 'Occasion updated successfully.');
    }

    public function destroy(Occasion $occasion)
    {
        if ($occasion->products()->exists()) {
            return redirect()->route('occasion')->with('error', 'Cannot delete occasion because it has products.');
        }

        $occasion->delete();

        return redirect()->route('occasion')->with('success', 'Occasion deleted successfully.');
    }
}
