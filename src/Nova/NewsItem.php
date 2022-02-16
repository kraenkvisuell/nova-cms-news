<?php

namespace Kraenkvisuell\NovaCmsNews\Nova;

use Illuminate\Http\Request;
use Kraenkvisuell\NovaCms\Facades\ContentBlock;
use Kraenkvisuell\NovaCmsMedia\MediaLibrary;
use Kraenkvisuell\NovaCmsNews\Nova\Filters\Published;
use Kraenkvisuell\NovaCmsPortfolio\Models\Artist;
use Kraenkvisuell\NovaCmsPortfolio\Nova\Artist as NovaArtist;
use Kraenkvisuell\NovaCmsPortfolio\Nova\Slideshow as NovaSlideshow;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;
use Manogi\Tiptap\Tiptap;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Timothyasp\Color\Color;

class NewsItem extends Resource
{
    use HasSortableRows;

    public static $model = \Kraenkvisuell\NovaCmsNews\Models\NewsItem::class;

    public static $sortable = false;

    public static $searchable = false;

    public static $perPageOptions = [500, 1000];

    public function title()
    {
        return $this->resource->title;
    }

    public static function label()
    {
        return 'News';
    }

    public static function singularLabel()
    {
        return 'News';
    }

    public function fields(Request $request)
    {
        $uploadOnly = config('nova-cms-news.media.upload_only') ?: false;

        return [
            MediaLibrary::make(__('nova-cms-news::news_items.main_image'), 'main_image')
                ->uploadOnly($uploadOnly),

            MediaLibrary::make(__('nova-cms-news::news_items.overview_image'), 'overview_image')
                ->uploadOnly($uploadOnly),

            Text::make(__('nova-cms::pages.title'), 'title')
                ->translatable(),

            Tiptap::make(__('nova-cms-news::news_items.abstract'), 'abstract')
                ->translatable(),

            // NovaBelongsToDepend::make('Artist', 'artist', NovaArtist::class)
            //     ->placeholder('choose artist')
            //     ->options(Artist::all())
            //     ->nullable(),

            // NovaBelongsToDepend::make('Project', 'slideshow', NovaSlideshow::class)
            //     ->placeholder('choose project')
            //     ->optionsResolve(function ($artist) {
            //         return $artist->slideshows()->get(['id', 'title']);
            //     })
            //     ->dependsOn('Artist')
            //     ->onlyOnForms()
            //     ->nullable(),

            // Select::make(__('nova-cms-portfolio::works.width_in_overview'), 'width_in_overview')
            //     ->options([
            //         'regular' => 'one column',
            //         'double' => 'two columns',
            //     ])
            //     ->required()
            //     ->default('regular')
            //     ->onlyOnForms(),

            // Select::make(__('nova-cms-portfolio::works.width_in_frame'), 'width_in_frame')
            //     ->options([
            //         'full' => 'full width',
            //         'two_thirds' => 'two thirds',
            //         'half' => 'half',
            //     ])
            //     ->onlyOnForms()
            //     ->default('full')
            //     ->required(),

            // Select::make(__('nova-cms-portfolio::works.title_position'), 'title_position')
            //     ->options([
            //         'bottom_left' => 'bottom left',
            //         'bottom_right' => 'bottom right',
            //         'top_left' => 'top left',
            //         'top_right' => 'top right',
            //     ])
            //     ->default('bottom_left')
            //     ->required()
            //     ->onlyOnForms(),

            Boolean::make(__('Veröffentlicht'), 'is_published'),

            Text::make('link', 'link')
                ->onlyOnForms(),

            // Text::make(__('nova-cms-portfolio::portfolio.subtitle'), 'subtitle')
            //     ->translatable()
            //     ->onlyOnForms(),

            ContentBlock::field(),

            // Color::make(__('nova-cms-portfolio::portfolio.background_color'), 'bgcolor')
            //     ->sketch()
            //     ->hideFromDetail(),

        ];
    }

    public function filters(Request $request)
    {
        return [
            new Published,
        ];
    }
}
