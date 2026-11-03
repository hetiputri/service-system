@php
    $id = $name . '_' . uniqid();
    $defaultFirst = filter_var($attributes->get('default-first', false), FILTER_VALIDATE_BOOLEAN);
@endphp

<div x-data>
    <select 
        x-ref="select"
        id="{{ $id }}"
        name="{{ $multiple ? $name.'[]' : $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->whereStartsWith('x-model') }}
        data-default-first="{{ $defaultFirst ? '1' : '0' }}"
        class="w-full font-bold placeholder:font-bold">
    </select>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    let selectElement = document.getElementById("{{ $id }}");
    let select = new TomSelect("#{{ $id }}", {
        valueField: "{{ $valueField }}",
        labelField: "{{ $labelField }}",
        searchField: "{{ $labelField }}",
        placeholder: "{{ $placeholder }}",
        preload: true,

        load: function(query, callback) {
            fetch('{{ url("master") }}/{{ $type }}')
                .then(res => res.json())
                .then(data => {
                    callback(data);

                    if (selectElement.dataset.defaultFirst === '1' && !select.getValue() && data.length > 0) {
                        select.setValue(data[0]["{{ $valueField }}"], true);
                        selectElement.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                })
                .catch(() => callback());
        },

        onChange: function(value) {
            let el = document.getElementById("{{ $id }}");

            // trigger input event supaya Alpine x-model update
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },

        render: {
            option: function(data, escape) {
                return `<div>${escape(data["{{ $labelField }}"])}</div>`;
            },
            item: function(data, escape) {
                return `<div>${escape(data["{{ $labelField }}"])}</div>`;
            }
        }
    });

});
</script>