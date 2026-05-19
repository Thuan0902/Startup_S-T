<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Storage;use Illuminate\Validation\ValidationException;

class PageContentController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $pages = config('page_content.pages', []);

        $data = $request->validate([
            'page' => ['required', 'string'],
            'content' => ['array'],
        ]);

        $page = $data['page'];

        if (!array_key_exists($page, $pages)) {
            throw ValidationException::withMessages([
                'page' => 'Trang này chưa được cấu hình để chỉnh sửa nội dung.',
            ]);
        }

        $allowedKeys = array_keys($pages[$page]['fields'] ?? []);
        $content = $data['content'] ?? [];

        foreach ($allowedKeys as $key) {
            $fieldType = $pages[$page]['fields'][$key]['type'] ?? 'text';
            $existingContent = PageContent::where('page', $page)->where('key', $key)->first();

            if ($fieldType === 'image' && $request->hasFile("content.$key")) {
                $file = $request->file("content.$key");
                $path = $file->store('page_content', 'public');

                PageContent::updateOrCreate(
                    ['page' => $page, 'key' => $key],
                    ['value' => $path]
                );
            } elseif ($fieldType === 'image') {
                if ($existingContent) {
                    PageContent::updateOrCreate(
                        ['page' => $page, 'key' => $key],
                        ['value' => $existingContent->value]
                    );
                }
            } else {
                PageContent::updateOrCreate(
                    ['page' => $page, 'key' => $key],
                    ['value' => $content[$key] ?? '']
                );
            }
        }

        return back()->with('content_editor_success', 'Đã lưu nội dung trang.');
    }
}
