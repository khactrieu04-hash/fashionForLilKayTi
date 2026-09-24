@props([
'component',
'props' => [],
])

<div
    data-react-component="{{ $component }}"
    data-react-props="{{ json_encode($props, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
    {{ $attributes }}></div>