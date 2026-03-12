<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Award;
use App\Models\CoreValue;
use App\Models\Founder;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    /** Show the admin about editor */
    public function edit()
    {
        $about    = About::firstOrCreate([]);
        $awards   = Award::orderBy('sort_order')->get();
        $values   = CoreValue::orderBy('sort_order')->get();
        $founders = Founder::orderBy('sort_order')->get();
        $faqs     = Faq::where('is_published', true)->orderBy('sort_order')->get();

        return view('admin.about', compact('about', 'awards', 'values', 'founders', 'faqs'));
    }

    /** Save everything on one submit */
    public function update(Request $request)
    {
        $about = About::firstOrCreate([]);

        // ---- About text fields ----
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

        // ---- Team photos ----
        foreach ([1, 2, 3] as $n) {
            $field = 'photo_' . $n;
            if ($request->boolean('remove_photo_' . $n) && $about->$field) {
                Storage::disk('public')->delete($about->$field);
                $about->update([$field => null]);
            }
            if ($request->hasFile($field)) {
                if ($about->$field) Storage::disk('public')->delete($about->$field);
                $about->update([$field => $request->file($field)->store('about', 'public')]);
            }
        }

        // ---- Update existing awards ----
        foreach ($request->input('awards', []) as $data) {
            $award = Award::find($data['id'] ?? null);
            if (!$award) continue;
            $award->update([
                'platform'    => $data['platform']    ?? $award->platform,
                'achievement' => $data['achievement'] ?? $award->achievement,
                'year'        => $data['year']        ?? $award->year,
                'sort_order'  => $data['sort_order']  ?? $award->sort_order,
            ]);
            // logo upload
            $key = 'awards.' . array_search($data, $request->input('awards')) . '.logo';
            if ($request->hasFile($key)) {
                if ($award->logo_path) Storage::disk('public')->delete($award->logo_path);
                $award->update(['logo_path' => $request->file($key)->store('awards', 'public')]);
            }
            if (!empty($data['remove_logo']) && $award->logo_path) {
                Storage::disk('public')->delete($award->logo_path);
                $award->update(['logo_path' => null]);
            }
        }

        // ---- Create new awards ----
        foreach ($request->input('new_awards', []) as $data) {
            if (empty($data['platform'])) continue;
            Award::create([
                'platform'    => $data['platform'],
                'achievement' => $data['achievement'] ?? '',
                'year'        => $data['year'] ?? date('Y'),
                'sort_order'  => Award::max('sort_order') + 1,
                'is_active'   => true,
            ]);
        }

        // ---- Update existing values ----
        foreach ($request->input('values', []) as $data) {
            $val = CoreValue::find($data['id'] ?? null);
            if (!$val) continue;
            $val->update([
                'icon'        => $data['icon']        ?? $val->icon,
                'title'       => $data['title']       ?? $val->title,
                'description' => $data['description'] ?? $val->description,
                'sort_order'  => $data['sort_order']  ?? $val->sort_order,
            ]);
        }

        // ---- Create new values ----
        foreach ($request->input('new_values', []) as $data) {
            if (empty($data['title'])) continue;
            CoreValue::create([
                'icon'        => $data['icon']        ?? '✨',
                'title'       => $data['title'],
                'description' => $data['description'] ?? '',
                'sort_order'  => CoreValue::max('sort_order') + 1,
                'is_active'   => true,
            ]);
        }

        // ---- Update existing founders ----
        foreach ($request->input('founders', []) as $i => $data) {
            $founder = Founder::find($data['id'] ?? null);
            if (!$founder) continue;
            $founder->update([
                'name'       => $data['name']       ?? $founder->name,
                'company'    => $data['company']    ?? $founder->company,
                'bio'        => $data['bio']        ?? $founder->bio,
                'website'    => $data['website']    ?? $founder->website,
                'linkedin'   => $data['linkedin']   ?? $founder->linkedin,
                'sort_order' => $data['sort_order'] ?? $founder->sort_order,
            ]);
            $photoKey = "founders.{$i}.photo";
            if ($request->hasFile($photoKey)) {
                if ($founder->photo) Storage::disk('public')->delete($founder->photo);
                $founder->update(['photo' => $request->file($photoKey)->store('founders', 'public')]);
            }
            if (!empty($data['remove_photo']) && $founder->photo) {
                Storage::disk('public')->delete($founder->photo);
                $founder->update(['photo' => null]);
            }
        }

        // ---- Create new founders ----
        foreach ($request->input('new_founders', []) as $data) {
            if (empty($data['name'])) continue;
            Founder::create([
                'name'       => $data['name'],
                'company'    => $data['company']  ?? '',
                'bio'        => $data['bio']       ?? '',
                'website'    => $data['website']   ?? null,
                'linkedin'   => $data['linkedin']  ?? null,
                'sort_order' => Founder::max('sort_order') + 1,
                'is_active'  => true,
            ]);
        }

        // ---- Update existing FAQs ----
        foreach ($request->input('faqs', []) as $data) {
            $faq = Faq::find($data['id'] ?? null);
            if (!$faq) continue;
            $faq->update([
                'question'   => $data['question']   ?? $faq->question,
                'answer'     => $data['answer']      ?? $faq->answer,
                'sort_order' => $data['sort_order']  ?? $faq->sort_order,
            ]);
        }

        // ---- Create new FAQs ----
        foreach ($request->input('new_faqs', []) as $data) {
            if (empty($data['question'])) continue;
            Faq::create([
                'question'     => $data['question'],
                'answer'       => $data['answer'] ?? '',
                'is_published' => true,
                'sort_order'   => Faq::max('sort_order') + 1,
            ]);
        }

        return redirect()->route('admin.about')->with('success', 'About page saved successfully!');
    }

    // ---- Delete individual items ----

    public function deleteAward(Request $request)
    {
        $award = Award::findOrFail($request->id);
        if ($award->logo_path) Storage::disk('public')->delete($award->logo_path);
        $award->delete();
        return redirect()->route('admin.about')->with('success', 'Award removed.');
    }

    public function deleteValue(Request $request)
    {
        CoreValue::findOrFail($request->id)->delete();
        return redirect()->route('admin.about')->with('success', 'Value removed.');
    }

    public function deleteFounder(Request $request)
    {
        $founder = Founder::findOrFail($request->id);
        if ($founder->photo) Storage::disk('public')->delete($founder->photo);
        $founder->delete();
        return redirect()->route('admin.about')->with('success', 'Founder removed.');
    }

    public function deleteFaq(Request $request)
    {
        Faq::findOrFail($request->id)->delete();
        return redirect()->route('admin.about')->with('success', 'FAQ removed.');
    }
}