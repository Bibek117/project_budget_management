  <div class="toast hide position-fixed bg-light border border-danger  border-3" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7000" style="z-index: 5; right: 5px; top: 110px;">
    <div class="toast-header border-bottom border-danger px-3 py-2 ">
      <strong class="mr-auto text-danger">Error</strong>
      <i class="bi bi-shield-exclamation text-danger"></i>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body mt-2 text-danger p-2">
      {{$message}}
    </div>
  </div>