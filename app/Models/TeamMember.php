<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMember extends Model {
    protected $fillable = [
        'name','slug','position','bio','photo',
        'expertise','education','achievements','gallery',
        'linkedin','twitter','instagram','facebook','github','youtube','whatsapp','website',
        'is_published','sort_order',
    ];

    protected $casts = [
        'expertise'    => 'array',
        'education'    => 'array',
        'achievements' => 'array',
        'gallery'      => 'array',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (TeamMember $member) {
            if (empty($member->slug)) {
                $base = Str::slug($member->name) ?: 'member';
                $slug = $base;
                for ($i = 2; static::where('slug', $slug)->where('id', '!=', $member->id ?? 0)->exists(); $i++) {
                    $slug = $base.'-'.$i;
                }
                $member->slug = $slug;
            }
        });
    }

    /** Network => URL, for the social icons. */
    public function socialLinks(): array
    {
        return array_filter([
            'linkedin'  => $this->linkedin,
            'instagram' => $this->instagram,
            'twitter'   => $this->twitter,
            'facebook'  => $this->facebook,
            'github'    => $this->github,
            'youtube'   => $this->youtube,
            'whatsapp'  => $this->whatsapp,
            'website'   => $this->website,
        ]);
    }
}
