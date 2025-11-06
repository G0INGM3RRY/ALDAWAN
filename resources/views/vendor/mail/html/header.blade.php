@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'ALDAWAN')
<span style="font-size: 24px; font-weight: bold; color: #0d6efd;">ALDAWAN</span>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
