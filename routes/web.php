<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Kinetics\Actions\Action;
use Kinetics\Actions\ActionGroup;
use Kinetics\Columns\ActionColumn;
use Kinetics\Columns\TextColumn;
use Kinetics\Filters\DateFilter;
use Kinetics\Filters\NumberFilter;
use Kinetics\Filters\SelectFilter;
use Kinetics\Filters\TextFilter;
use Kinetics\Table;

Route::get('/', function () {
    $posts = Table::model(Post::class)
        ->columns([
            TextColumn::make('id')
                ->hidden(),

            TextColumn::make('title')
                ->sortable()
                ->searchable(),

            // TextColumn::make('user_name')
            //     ->relation('user', 'name')
            //     ->label('Author')
            //     ->sortable()
            //     ->searchable(),

            // or

            TextColumn::make('user.name')
                ->label('Author')
                ->sortable()
                ->searchable(),

            TextColumn::make('user.role')
                ->label('Role')
                ->badge()
                ->searchable(),

            TextColumn::make('category.name')
                ->badge()
                ->color('outline')
                ->sortable(),

            // formatting value relation

            // TextColumn::make('category_format')
            //     ->relation('category', 'name')
            //     ->formatUsing(function ($val) {
            //         return 'gg-' . $val;
            //     })

            //     ->badge()
            //     ->color('outline')
            //     ->sortable(),

            TextColumn::make('status')
                ->badge(),

            TextColumn::make('is_featured')
                ->label('Featured')
                ->badge(),

            TextColumn::make('views_count')
                ->label('Views')
                ->sortable(),

            TextColumn::make('likes_count')
                ->label('Likes')
                ->sortable(),

            TextColumn::make('published_at')
                ->label('Published')
                ->date('d/m/Y')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Created')
                ->date('d/m/Y')
                ->sortable(),

            ActionColumn::make()
                ->actions([
                    Action::view('posts.edit')->href(''),

                    ActionGroup::make()
                        ->actions([
                            Action::make('publish')
                                ->label('Publish')
                                ->icon('check-circle')
                                ->variant('default')
                                ->method('PATCH')
                                ->visibleWhen(fn ($row) => $row['status'] !== 'published')
                                ->confirm('Publish this post?'),

                            Action::make('archive')
                                ->label('Archive')
                                ->icon('ban')
                                ->variant('default')
                                ->method('PATCH')
                                ->visibleWhen(fn ($row) => $row['status'] === 'published')
                                ->confirm('Archive this post?'),
                        ]),
                ]),
        ])
        ->filters([
            SelectFilter::make('user.role')->label('Role')->options([
                'admin' => 'Administrator',
                'user' => 'Regular User',
            ]),
            SelectFilter::make('status')->options(['published', 'archived', 'draft']),
            TextFilter::make('title'),
            DateFilter::make('published_at')->label('Publish')->range(),
            NumberFilter::make('likes_count')->label('Likes')->range(),
        ])
        ->actions([
            Action::make('sada')->label('Tambah')->icon('plus'),
        ])
        ->defaultSort('created_at', 'desc')
        ->debounce(500)
        ->make();

    return Inertia::render('posts', ['posts' => $posts]);
});
