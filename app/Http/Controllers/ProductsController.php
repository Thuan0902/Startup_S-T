<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $status = (string) request('status', '');
        $categoryFilter = (string) request('category_id', '');
        $occasionFilter = (string) request('occasion_id', '');

        $products = Product::with(['category', 'occasion'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('advantages', 'like', "%{$search}%")
                        ->orWhere('disadvantages', 'like', "%{$search}%")
                        ->orWhere('affiliate_link', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->when(ctype_digit($categoryFilter), function ($query) use ($categoryFilter) {
                $query->where('category_id', (int) $categoryFilter);
            })
            ->when(ctype_digit($occasionFilter), function ($query) use ($occasionFilter) {
                $query->where('occasion_id', (int) $occasionFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $occasions = Occasion::orderBy('name')->get();

        return view('Admin.products', compact('products', 'categories', 'occasions', 'search', 'status', 'categoryFilter', 'occasionFilter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'disadvantages' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'affiliate_link' => ['nullable', 'url', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'occasion_id' => ['nullable', 'exists:occasions,id'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['views'] = 0;
        $validated['occasion_id'] = $request->input('occasion_id') ?: null;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            $validated['image'] = null;
        }

        Product::create($validated);

        return redirect()->route('products')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'disadvantages' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'affiliate_link' => ['nullable', 'url', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'occasion_id' => ['nullable', 'exists:occasions,id'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['occasion_id'] = $request->input('occasion_id') ?: null;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products')->with('success', 'Product deleted successfully.');
    }
}
