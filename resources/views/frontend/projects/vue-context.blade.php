@extends('layouts.single-project')
@section('metaDescription', 'vue-context provides a simple yet flexible context menu for Vue. It is styled for the standard ul tag, but any menu template can be used.')

@section('description')
    <p>
        vue-context provides a simple yet flexible context menu for Vue. It is styled for the standard
        <code>&lt;ul&gt;</code> tag, but any menu template can be used. The menu is lightweight with its only
        dependencies being <code>vue-clickaway</code> and <code>core-js</code>. The menu has some basic styles applied to it but
        they can be easily overridden by your own styles.
    </p>

    <p>
        The menu can be configured to disappear on scroll and when clicked on. Any clicks outside the
        menu will always close it.
    </p>
@endsection

@section('version')
    4.0.0
@endsection

@section('date')
    August 18, 2017
@endsection
