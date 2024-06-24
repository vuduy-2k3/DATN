<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}",{ iconClass: 'toast-success-icon' });
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}", { iconClass: 'toast-error-icon' });
    @endif
</script>