@props(['name'])

<x-dynamic-component :component="'icons.' . $name" {{ $attributes }} />
