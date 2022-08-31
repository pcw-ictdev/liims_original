@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<div class="box box-primary">
  <div class="box-header with-border">
      <h4><b> HEADER </b></h3>
  </div> <!-- /.box-header -->
  <form method="POST" action="/">
  {{ csrf_field() }}
    <div class="box-body">
      BODY
    </div> <!-- /.box-body -->
    <div class="box-footer">
      FOOTER
    </div> <!-- /.box-footer -->
  </form>
</div> <!-- /.box box-primary -->
@endsection