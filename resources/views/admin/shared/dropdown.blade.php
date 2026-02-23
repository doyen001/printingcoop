<?php
    /**
     * @param id (optional)
     * @param name
     * @param urlFunction or url
     * @param value (optional)
     * @param onchange (optional)
     * @param class (optional)
     * @param label (optional)
     * @param required (optional)
     * @param fieldText (optional)
     * @param fieldId (optional)
     */
    $id = $data['id'] ?? $data['name'];
?>
<input class="form-control k-input {{ $data['class'] ?? '' }}" id="{{ $id }}" name="{{ $data['name'] }}" style="width: 100%;" type="text" value="{{ $data['value'] ?? '' }}" {{ ($data['required'] ?? false) ? 'required' : '' }} />

<script>
    $(document).ready(function () {
        $('#{{ str_replace(['[', ']'], '_', $id) }}').kendoDropDownList({
            autobind: false,
            optionLabel: "{{ $data['label'] ?? '' }}",
            filter: "startswith",
            dataSource: {
                transport: {
                    read: {
                        url: {{ isset($data['urlFunction']) ? $data['urlFunction'] : ("'" . $data['url'] . "'") }},
                        type: 'POST',
                        dataType: 'json',
                    }
                },
                schema: {
                    data: "data"
                },
                serverFiltering: true
            },
            dataTextField: "{{ $data['fieldText'] ?? 'name' }}",
            dataValueField: "{{ $data['fieldId'] ?? 'id' }}",
            @if ($data['template'] ?? false)
                template: '{{ $data['template'] }}',
            @endif
            @if ($data['onchange'] ?? false)
                change: {{ $data['onchange'] }},
                dataBound: {{ $data['onchange'] }},
            @endif
        });
    });
</script>
