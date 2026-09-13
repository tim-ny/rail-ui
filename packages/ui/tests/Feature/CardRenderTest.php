<?php

namespace Aegis\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;

it('renders basic card with content', function () {
    $rendered = Blade::render('<x-card><p>Card content</p></x-card>');

    expect($rendered)
        ->toContain('ui-card')
        ->toContain('Card content')
        ->toContain('ui-card__body')
        ->toContain('ui-card__content');
});

it('renders card with header slot', function () {
    $rendered = Blade::render('<x-card><x-slot:header><h3>Title</h3></x-slot:header><p>Body</p></x-card>');

    expect($rendered)
        ->toContain('ui-card__header')
        ->toContain('Title');
});

it('renders card with footer slot', function () {
    $rendered = Blade::render('<x-card><p>Body</p><x-slot:footer><button>Action</button></x-slot:footer></x-card>');

    expect($rendered)
        ->toContain('ui-card__footer')
        ->toContain('Action');
});

it('renders card with media slot', function () {
    $rendered = Blade::render('<x-card><x-slot:media><img src="photo.jpg" /></x-slot:media><p>Body</p></x-card>');

    expect($rendered)
        ->toContain('ui-card__media')
        ->toContain('photo.jpg');
});

it('renders card as link when href is provided', function () {
    $rendered = Blade::render('<x-card href="/about"><p>About us</p></x-card>');

    expect($rendered)
        ->toContain('<a href="/about"')
        ->toContain('ui-card--link')
        ->toContain('</a>');
});

it('renders card as div when no href', function () {
    $rendered = Blade::render('<x-card><p>Content</p></x-card>');

    expect($rendered)
        ->toContain('<div class="ui-card')
        ->not->toContain('<a href');
});

it('renders card without border when border is false', function () {
    $rendered = Blade::render('<x-card :border="false"><p>No border</p></x-card>');

    expect($rendered)
        ->not->toContain('ui-card--border');
});

it('renders overflow class when overflow is true', function () {
    $rendered = Blade::render('<x-card :overflow="true"><p>Overflow</p></x-card>');

    expect($rendered)
        ->toContain('ui-card--overflow');
});

it('passes attributes through', function () {
    $rendered = Blade::render('<x-card class="custom" id="my-card"><p>Content</p></x-card>');

    expect($rendered)
        ->toContain('class="ui-card')
        ->toContain('id="my-card"');
});

it('renders nested slots correctly', function () {
    $rendered = Blade::render('<x-card>
        <x-slot:media><img src="hero.jpg" /></x-slot:media>
        <x-slot:header><h2>Product</h2></x-slot:header>
        <p>Description text</p>
        <x-slot:footer><button>Buy</button></x-slot:footer>
    </x-card>');

    expect($rendered)
        ->toContain('ui-card__media')
        ->toContain('ui-card__header')
        ->toContain('ui-card__content')
        ->toContain('ui-card__footer')
        ->toContain('hero.jpg')
        ->toContain('Product')
        ->toContain('Description text')
        ->toContain('Buy');
});
