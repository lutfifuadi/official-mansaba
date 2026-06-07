@if ($errors->any())
  <div {{ $attributes->merge(['class' => 'alert alert-danger alert-dismissible mb-4']) }} role="alert">
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
