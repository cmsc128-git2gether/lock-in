{{-- server error --}}
@extends('errors.layout', ['code' => 500, 'title' => 'Something went wrong', 'message' => 'It’s on our side. Please try again in a moment.'])