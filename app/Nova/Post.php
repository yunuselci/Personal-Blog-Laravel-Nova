<?php

namespace App\Nova;

use Ek0519\Quilljs\Quilljs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use YesWeDev\Nova\Translatable\Translatable;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make('User'),

            Text::make('Url', function () {
                return "<a href='{$this->url}'>Go to the Post</a>";
            })
                ->asHtml()
                ->showOnIndex()
                ->showOnDetail(),

            Translatable::make('Title')
                ->singleLine()
                ->rules('required'),

            Translatable::make('Subtitle')
                ->singleLine(),

            Translatable::make('Slug')
                ->singleLine(),

            Image::make('Image')
                ->disk('public')
                ->required(),

            Quilljs::make('Description')
                ->withFiles('public')
                ->rules('required'),

            Text::make('Link')
                ->nullable(),

            Translatable::make(__('Published (1 True/ 0 False)'), 'published')
                ->nullable()
                ->singleLine(),

            Translatable::make(__('Publish to Dev.to (1 True/ 0 False)'), 'publish_to_dev_to')
                ->nullable()
                ->singleLine(),

        ];
    }

    public static function afterCreate(Request $request, $model)
    {
        event(new PostCreatedEvent($model));
    }

    public static function afterUpdate(Request $request, $model)
    {
        foreach ($model->translations as $translation) {
            if (blank($model->translateOrNew($translation->locale)->dev_to_article_id)) {
                event(new PostCreatedEvent($model->translateOrNew($translation->locale)));
            }
        }
        event(new PostUpdatedEvent($model));
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
