<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Award;
use App\Models\CoreValue;
use App\Models\Founder;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    private function uploadFile($file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        if (!file_exists($dir)) mkdir($dir, 0755, true);
        $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'uploads/' . $folder . '/' . $name;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) unlink(public_path($path));
    }

    public function edit()
    {
        $about    = About::firstOrCreate([]);
        $awards   = Award::orderBy('sort_order')->get();
        $values   = CoreValue::orderBy('sort_order')->get();
        $founders = Founder::orderBy('sort_order')->get();
        $faqs     = Faq::where('is_published', true)->orderBy('sort_order')->get();
        return view('admin.about.edit', compact('about', 'awards', 'values', 'founders', 'faqs'));
    }

    public function update(Request $request)
    {
        $about = About::firstOrCreate([]);

        $about->update($request->only([
            'hero_tag','hero_title','hero_subtitle',
            'mission_title','mission_text_1','mission_text_2',
            'stats_label',
            'stat_clients_num','stat_clients_label',
            'stat_projects_num','stat_projects_label',
            'stat_satisfaction_num','stat_satisfaction_label',
            'stat_experience_num','stat_experience_label',
            'milestones_tag','milestones_title','milestones_subtitle',
            'values_tag','values_title','values_subtitle',
            'faq_tag','faq_title','faq_subtitle',
        ]));

        foreach ([1, 2, 3] as $n) {
            $field = 'photo_' . $n;
            if ($request->boolean('remove_photo_' . $n) && $about->$field) {
                $this->deleteFile($about->$field);
                $about->update([$field => null]);
            }
            if ($request->hasFile($field)) {
                $this->deleteFile($about->$field);
                $about->update([$field => $this->uploadFile($request->file($field), 'about')]);
            }
        }

        foreach ($request->input('awards', []) as $i => $data) {
            $award = Award::find($data['id'] ?? null);
            if (!$award) continue;
            $award->update([
                'platform'    => $data['platform']    ?? $award->platform,
                'achievement' => $data['achievement'] ?? $award->achievement,
                'year'        => $data['year']        ?? $award->year,
                'sort_order'  => $data['sort_order']  ?? $award->sort_order,
            ]);
            $key = "awards.{$i}.logo";
            if ($request->hasFile($key)) {
                $this->deleteFile($award->logo_path);
                $award->update(['logo_path' => $this->uploadFile($request->file($key), 'awards')]);
            }
            if (!empty($data['remove_logo'])) {
                $this->deleteFile($award->logo_path);
                $award->update(['logo_path' => null]);
            }
        }

        foreach ($request->input('new_awards', []) as $data) {
            if (empty($data['platform'])) continue;
            Award::create(['platform' => $data['platform'], 'achievement' => $data['achievement'] ?? '', 'year' => $data['year'] ?? date('Y'), 'sort_order' => Award::max('sort_order') + 1, 'is_active' => true]);
        }

        foreach ($request->input('values', []) as $data) {
            $val = CoreValue::find($data['id'] ?? null);
            if (!$val) continue;
            $val->update(['icon' => $data['icon'] ?? $val->icon, 'title' => $data['title'] ?? $val->title, 'description' => $data['description'] ?? $val->description, 'sort_order' => $data['sort_order'] ?? $val->sort_order]);
        }

        foreach ($request->input('new_values', []) as $data) {
            if (empty($data['title'])) continue;
            CoreValue::create(['icon' => $data['icon'] ?? '✨', 'title' => $data['title'], 'description' => $data['description'] ?? '', 'sort_order' => CoreValue::max('sort_order') + 1, 'is_active' => true]);
        }

        foreach ($request->input('founders', []) as $i => $data) {
            $founder = Founder::find($data['id'] ?? null);
            if (!$founder) continue;
            $founder->update(['name' => $data['name'] ?? $founder->name, 'company' => $data['company'] ?? $founder->company, 'bio' => $data['bio'] ?? $founder->bio, 'website' => $data['website'] ?? $founder->website, 'linkedin' => $data['linkedin'] ?? $founder->linkedin, 'sort_order' => $data['sort_order'] ?? $founder->sort_order]);
            $photoKey = "founders.{$i}.photo";
            if ($request->hasFile($photoKey)) {
                $this->deleteFile($founder->photo);
                $founder->update(['photo' => $this->uploadFile($request->file($photoKey), 'founders')]);
            }
            if (!empty($data['remove_photo'])) {
                $this->deleteFile($founder->photo);
                $founder->update(['photo' => null]);
            }
        }

        foreach ($request->input('new_founders', []) as $data) {
            if (empty($data['name'])) continue;
            Founder::create(['name' => $data['name'], 'company' => $data['company'] ?? '', 'bio' => $data['bio'] ?? '', 'website' => $data['website'] ?? null, 'linkedin' => $data['linkedin'] ?? null, 'sort_order' => Founder::max('sort_order') + 1, 'is_active' => true]);
        }

        foreach ($request->input('faqs', []) as $data) {
            $faq = Faq::find($data['id'] ?? null);
            if (!$faq) continue;
            $faq->update(['question' => $data['question'] ?? $faq->question, 'answer' => $data['answer'] ?? $faq->answer, 'sort_order' => $data['sort_order'] ?? $faq->sort_order]);
        }

        foreach ($request->input('new_faqs', []) as $data) {
            if (empty($data['question'])) continue;
            Faq::create(['question' => $data['question'], 'answer' => $data['answer'] ?? '', 'is_published' => true, 'sort_order' => Faq::max('sort_order') + 1]);
        }

        return redirect()->route('admin.about.edit')->with('success', 'About page saved successfully!');
    }

    public function deleteAward(Request $request)
    {
        $award = Award::findOrFail($request->id);
        $this->deleteFile($award->logo_path);
        $award->delete();
        return redirect()->route('admin.about.edit')->with('success', 'Award removed.');
    }

    public function deleteValue(Request $request)
    {
        CoreValue::findOrFail($request->id)->delete();
        return redirect()->route('admin.about.edit')->with('success', 'Value removed.');
    }

    public function deleteFounder(Request $request)
    {
        $founder = Founder::findOrFail($request->id);
        $this->deleteFile($founder->photo);
        $founder->delete();
        return redirect()->route('admin.about.edit')->with('success', 'Founder removed.');
    }

    public function deleteFaq(Request $request)
    {
        Faq::findOrFail($request->id)->delete();
        return redirect()->route('admin.about.edit')->with('success', 'FAQ removed.');
    }
}