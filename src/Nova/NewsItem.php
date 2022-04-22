<?php

namespace Kraenkvisuell\NovaCmsNews\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Kraenkvisuell\NovaCms\Facades\ContentBlock;
use Kraenkvisuell\NovaCms\Tabs\Seo;
use Kraenkvisuell\NovaCmsMedia\MediaLibrary;
use Kraenkvisuell\NovaCmsNews\Nova\Filters\Published;
use Kraenkvisuell\NovaCmsPortfolio\Models\Artist;
use Kraenkvisuell\NovaCmsPortfolio\Nova\Artist as NovaArtist;
use KraenkVisuell\NovaSortable\Traits\HasSortableRows;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Manogi\Tiptap\Tiptap;

class NewsItem extends Resource
{
    use HasSortableRows;
    use TabsOnEdit;

    public static $model = \Kraenkvisuell\NovaCmsNews\Models\NewsItem::class;

    public static $sortable = false;

    public static $searchable = false;

    public static $perPageOptions = [500, 1000];

    public function title()
    {
        return $this->resource->title;
    }

    public static function sortableHasDropdown()
    {
        return config('nova-cms-news.with_sortable_dropdown') ?: false;
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

        $tabs = [];

        $tabs[__('nova-cms::pages.content')] = [
            MediaLibrary::make(__('nova-cms-news::news_items.main_image'), 'main_image')
                ->uploadOnly($uploadOnly),

            MediaLibrary::make(__('nova-cms-news::news_items.overview_image'), 'overview_image')
                ->uploadOnly($uploadOnly),

            Text::make(__('nova-cms::pages.title'), 'title')
                ->translatable(),

            Text::make(__('nova-cms::pages.slug'), 'slug')
                ->required()
                ->rules('required')
                ->onlyOnForms(),

            Tiptap::make(__('nova-cms-news::news_items.abstract'), 'abstract')
                ->translatable(),

        ];

        $tabs[__('nova-cms::pages.content')][] = Boolean::make(__('Veröffentlicht'), 'is_published');

        // if (config('nova-cms-news.with-portfolio')) {
        //     $tabs[__('nova-cms::pages.content')][] = BelongsTo::make('Artist', 'artist', NovaArtist::class)
        //         ->placeholder('choose artist')
        //         ->nullable();
        // }

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

        $tabs[__('nova-cms::pages.content')][] = Text::make('link', 'link')
            ->onlyOnForms();

        // Text::make(__('nova-cms-portfolio::portfolio.subtitle'), 'subtitle')
        //     ->translatable()
        //     ->onlyOnForms(),

        $tabs[__('nova-cms::pages.content')][] = ContentBlock::field();

        // Color::make(__('nova-cms-portfolio::portfolio.background_color'), 'bgcolor')
        //     ->sketch()
        //     ->hideFromDetail(),

        $tabs[__('nova-cms::seo.seo')] = Seo::make();

        return [
            (new Tabs(static::singularLabel(), $tabs))->withToolbar(),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new Published,
        ];
    }
}
