{{-- session expired --}}

@extends('errors.layout', ['code' => 419, 'title' => 'Session expired', 'message' => 'Your session timed out. Go back, refresh the page and try again.'])