<?php
    /**
     * @param id (optional)
     * @param name
     * @param value (optional)
     * @param msg_required (optional)
     * @param class (optional)
     * @param placeholder (optional)
     * @param required (optional)
     * @param min (optional)
     * @param max (optional)
     */
    $id = isset($id) ? $id : $name;
?>
<input data-val="true" {{ (isset($msg_required)) ? "data-val-required=\"$msg_required\"" : '' }} id="{{ $id }}" name="{{ $name }}" type="text" value="{{ isset($value) ? $value : '' }}" class="{{ isset($class) ? $class : '' }}" placeholder="{{ isset($placeholder) ? $placeholder : '' }}" {{ isset($required) && $required ? 'required' : '' }} />
<script>
    $(document).ready(function() {
        $('#{{ str_replace(['[', ']'], '_', $id) }}').kendoNumericTextBox({
            format: "#",
            decimals: 0,
            @if (isset($min))
                min: {{ $min }},
            @endif
            @if (isset($max))
                max: {{ $max }},
            @endif
        });
    });
</script>
