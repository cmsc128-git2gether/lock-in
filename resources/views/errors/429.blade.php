{{-- too many attempts --}}
@extends('errors.layout', ['code' => 429, 'title' => 'Too many attempts', 'message' => 'Please wait a minute and try again.'])