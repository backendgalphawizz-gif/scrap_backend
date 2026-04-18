<div class="modal fade" id="{{ $modal_id ?? 'image-crop-modal' }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: {{ $width ?? 600 }}px; margin-left: {{ $margin_left ?? 'auto' }}; margin-right: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CPU\translate('Image Crop') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0 text-muted">{{ \App\CPU\translate('Image preview is available in the form. Crop tool is currently not configured.') }}</p>
            </div>
        </div>
    </div>
</div>
