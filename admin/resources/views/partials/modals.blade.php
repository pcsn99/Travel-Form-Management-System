{{-- resources/views/partials/modals.blade.php --}}

@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{!! session('success') !!}</div>
            <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal('#successModal').show());</script>
@endif

@if(session('error'))
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle-fill me-2"></i>Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{!! session('error') !!}</div>
            <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal('#errorModal').show());</script>
@endif

@if(session('warning'))
<div class="modal fade" id="warningModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{!! session('warning') !!}</div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => new bootstrap.Modal('#warningModal').show());</script>
@endif
