<script language="JavaScript">
    @foreach($varsJs as $key => $value)
        @if(is_array($value))
            var {{ $key }} = {!! json_encode($value) !!};
        @else
            var {{ $key }} = '{{ $value }}';
        @endif
    @endforeach
</script>