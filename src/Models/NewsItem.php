<?php

namespace Kraenkvisuell\NovaCmsNews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kraenkvisuell\NovaCms\Traits\HasContentBlocks;
use Kraenkvisuell\NovaCmsBlocks\Value\BlocksCast;
use Kraenkvisuell\NovaCmsPortfolio\Models\Artist;
use Kraenkvisuell\NovaCmsPortfolio\Models\Slideshow;
use Kraenkvisuell\NovaCmsPortfolio\Traits\Publishable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class NewsItem extends Model implements Sortable
{
    use HasFactory;
    use Publishable;
    use SortableTrait;
    use HasTranslations;
    use HasContentBlocks;

    public $sortable = [
        'order_column_name' => 'sort_order',
    ];

    protected $guarded = [];

    protected $casts = [
        'main_content' => BlocksCast::class,
    ];

    public $contentBlockFields = [
        'main_content',
    ];

    public $translatable = [
        'title',
        'subtitle',
        'abstract',
        'browser_title',
        'meta_description',
        'meta_keywords',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function slideshow()
    {
        return $this->belongsTo(Slideshow::class);
    }

    public function imageFile()
    {
        if ($this->image) {
            return $this->image;
        }

        if ($this->slideshow) {
            return optional($this->slideshow->workForNews())->file;
        }

        return 0;
    }

    public function getLink()
    {
        if (trim($this->link)) {
            return nova_cms_parse_link(trim($this->link));
        }

        if ($this->slideshow) {
            return route('slideshow', [$this->slideshow->artist->slug, $this->slideshow->slug]);
        }

        if ($this->artist) {
            return route('artist', [$this->artist->disciplines->first()->slug, $this->artist->slug]);
        }

        return '#';
    }

    public function getTarget()
    {
        if (trim($this->link)) {
            return '_blank';
        }

        return '_self';
    }

    public function getTitle()
    {
        if ($this->title) {
            return $this->title;
        }

        if ($this->slideshow) {
            return $this->slideshow->artist->name;
        }
    }

    public function getSubtitle()
    {
        if ($this->subtitle) {
            return $this->subtitle;
        }

        if ($this->slideshow) {
            return $this->slideshow->title;
        }
    }
}
