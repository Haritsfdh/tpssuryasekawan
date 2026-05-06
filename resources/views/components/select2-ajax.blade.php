
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="form-control select2-ajax"
        style="width: {{ $width ?? '100%' }};"
        @if(!empty($required)) required @endif>
    {{ $slot }}
    </select>



@push('scripts')
<script>
$(document).ready(function(){
    $('#{{ $id }}').select2({
        placeholder: '{{ $placeholder }}',
        ajax: {
            url: '{{ $route }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});
</script>
@endpush
