@extends('layouts.app')

@section('content')
<div id="successModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a href="/purchased">Check your Purchase history</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"><a href="/">Back to Home</a></button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();
    });
</script>