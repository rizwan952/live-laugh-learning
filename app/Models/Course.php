<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function languageLevelFrom()
    {
        return $this->belongsTo(LanguageLevel::class, 'language_level_from_id');
    }

    public function languageLevelTo()
    {
        return $this->belongsTo(LanguageLevel::class, 'language_level_to_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, CourseTag::class);
    }

    public function durations()
    {
        return $this->hasMany(CourseDuration::class);
    }

}
