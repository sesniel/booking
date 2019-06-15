@extends('layouts.public')

@section('content')
    <div class="wb-action-banner post-job"></div>
    <section id="wb-job-list" class="wb-bg-grey" style="padding: 40px 0px">
        <div class="container">
            <div class="text-center">
                <h4> {{ $exception->getMessage() ?: 'Sorry, You are not authorize to perform this action.' }}</h4>
            </div>
        </div>
    </section>
@endsection
