@extends('layouts.app')

@section('body')
<div class="container">
    <div class="col">
        <form action="{{ route('store') }}" method="post">
            @csrf
            <div class="row my-5 d-flex justify-content-center">
                <div class="d-grid col-6 mx-auto">
                    <label for="original" class="form-label">Исходная ссылка</label>
                    <input type="text" name="original" id="original" class="form-control form-control-sm" placeholder="Пример: https://www.google.com/">
                    <button type="submit" class="btn btn-success btn-sm btn-block mt-5">Сократить и сохранить</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="table-responsive col-6 mx-auto">
                <table class="table table-sm table-striped table-hover" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Исходная ссылка</th>
                        <th>Сокращенная ссылка</th>
                    </tr>
                    </thead>
                    <tbody id="urls">
                        @foreach ($urls as $url)
                            <tr>
                                <td>{{ $url->id }}</td>
                                <td>{{ $url->original }}</td>
                                <td><a href="{{ route('show', $url) }}" target="_blank">{{ route('show', $url->shortened) }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('form').submit(function(e){
        var url = window.location.origin
        var formData = {
            original: $('#original').val()
        }
        $('#error-message').remove()
        e.preventDefault()
        $.ajax({
            type:'POST',
            url:'/',
            data: formData,
            dataType: "json",
            encode: true,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(response){
                $('#urls').append(`
                    <tr>
                        <td>${response.url.id}</td>
                        <td>${response.url.original}</td>
                        <td><a href="${response.url.original}" target="_blank">${url}/${response.url.shortened}</a></td>
                    </tr>
                `)
                $('#original').val('')
            },
            error:(function(message) {
                $.each(message.responseJSON.errors, function(index, value){
                    $('#original').after(`<div id="error-message" class="text-danger fs-6">${value}</div>`)
                })
            })
        });
    })
})
</script>
@endsection
