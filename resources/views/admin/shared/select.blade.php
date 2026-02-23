<?php
    /**
     * @param items (list of [text, value])
     * @param id (optional)
     * @param name
     * @param value (optional)
     * @param msg_required (optional)
     * @param class (optional)
     * @param index (optional) true => option value will be index of item
     * @param required (optional)
     * @param disabled (optional)
     */
    $id = isset($id) ? $id : $name;
    if (!isset($index))
        $index = false;
?>
@if (isset($items))
<select class="form-control {{ isset($class) ? $class : ''}}" data-val="true" {{ isset($msg_required) ? 'data-val-required="' . $msg_required . '"' : ''}} id="{{ $id}}" name="{{ $name}}" {{ isset($required) && $required ? 'required' : ''}} {{ isset($disabled) && $disabled ? 'disabled' : ''}}>
    @foreach ($items as $key => $item)
        @if (is_array($item) && isset($item['text']))
            <option {{ (((isset($value) && $item['value'] == $value) || (isset($item['selected']) && $item['selected'])) ? 'selected="selected"' : '') }} value="{{ $index ? $key : $item['value'] }}">{{ $item['text'] }}</option>
        @elseif (is_array($item) && isset($item['name']))
            <option {{ (((isset($value) && $item['value'] == $value) || (isset($item['selected']) && $item['selected'])) ? 'selected="selected"' : '') }} value="{{ $index ? $key : $item['value'] }}">{{ $item['name'] }}</option>
        @elseif (is_array($item) && isset($item['title']))
            <option {{ (((isset($value) && $item['value'] == $value) || (isset($item['selected']) && $item['selected'])) ? 'selected="selected"' : '') }} value="{{ $index ? $key : $item['value'] }}">{{ $item['title'] }}</option>
        @elseif (is_object($item) && isset($item->name))
            <option {{ (((isset($value) && $item->id == $value) || (isset($item->selected) && $item->selected)) ? 'selected="selected"' : '') }} value="{{ $index ? $key : $item->id }}">{{ $item->name }}</option>
        @else
            <option {{ ((isset($value) && ($index ? $key : $item) == $value) ? 'selected="selected"' : '') }} value="{{ $index ? $key : $item }}">{{ $item }}</option>
        @endif
    @endforeach
</select>
<script>
    $(document).ready(function() {
        $('#{{ str_replace(['[', ']'], '_', $id) }}').kendoDropDownList();
    });
</script>
@endif
