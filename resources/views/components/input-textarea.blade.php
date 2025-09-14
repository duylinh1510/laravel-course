@props(['disabled' => false])


{{-- @props định nghĩa props cho component.

@disabled($disabled) giúp bật/tắt thuộc tính disabled.

{{ $attributes }} để merge thêm các attribute khác. --}}

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
{{ $slot }}
</textarea>

