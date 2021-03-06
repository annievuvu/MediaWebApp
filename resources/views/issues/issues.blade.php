@extends('layouts.app')

@section('content')
<div class='container'>
<div class='col-lg-10 col-md-9 col-sm-8 col-xs-7 col-lg-offset-1 col-md-offset-1 col-sm-offset-2 col-xs-offset-3'>
@if(count($needsWork) > 0 or count($workingOn) > 0 )
@if(count($needsWork) > 0)
<div class='table-responsive' style='overflow-x:hidden;'>
	<h2> {{count($needsWork)}}
	@if(count($needsWork)> 1)
	 Things Need
	 @else
	 Thing Needs
	 @endif
	 Work</h2>
	<table class='table table-hover table-striped table-responsive'>
		<thead>
			<tr>
				<th style='text-align:center'>Issue ID</th>
				<th style='text-align:center'>Item</th>
				<th style='text-align:center'>Room</th>
				<th style='text-align:center'>Submitted By</th>
				<th style='text-align:center'>Priority</th>
				<th style='text-align:center'>Created On</th>
			</tr>
		</thead>
		@foreach($needsWork as $need)
		<tbody>
			<tr id="{{$need->id}}" data-toggle="collapse" data-target="#{{$need->id}}_item">
				<td style='vertical-align:middle;'>
					<h5><a href='/issues/{{$need->id}}'>{{$need->id}}</a></h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$need->asset->type->name}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$need->asset->container->barcode}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$need->user->first_name}} {{$need->user->last_name}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$need->priority}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{date('D M j g:i:s',strtotime($need->created_at))}}</h5>
				</td>
			</tr>
			<tr id="{{$need->id}}_item" class="collapse" style="margin-left: auto; margin-right: auto; ">
				<td colspan= "6" style="margin-left: auto; margin-right: auto; ">{{$need->comments[0]->comment}}</td>
			</tr>
		</tbody>
		@endforeach
	</table>
</div>
@endif
@if(count($workingOn) > 0)
<div class='table-responsive' style='overflow-x:hidden;'>
	<h2> {{count($workingOn)}}
	 @if(count($workingOn)> 1)
	 things are
	 @else
	 thing is
	 @endif
	 being Worked On</h2>
	<table class='table table-hover table-striped table-responsive'>
		<thead>
			<tr>
				<th style='text-align:center'>Issue ID</th>
				<th style='text-align:center'>Item</th>
				<th style='text-align:center'> Room </th>
				<th style='text-align:center'>Comment</th>
				<th style='text-align:center'>Submitted By</th>
				<th style='text-algin:center'>Priority</th>
				<th style='text-align:center'>Status</th>
				<th style='text-align:center'>Created On</th>
			</tr>
		</thead>
		@foreach($workingOn as $work)
		<tbody>
			<tr id="{{$work->id}}" data-toggle="collapse" data-target="#{{$work->id}}_item">
				<td style='vertical-align:middle;'>
					<h5><a href='issues/{{$work->id}}'>{{$work->id}}</a></h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->asset->type->name}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->asset->container->barcode}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->comments[0]->comment}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->user_id}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->priority}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{$work->status}}</h5>
				</td>
				<td style='vertical-align:middle;'>
					<h5>{{date('D M j g:i:s',strtotime($work->created_at))}}</h5>
				</td>
			</tr>
			<tr id="{{$work->id}}_item" class="collapse list-group" style="margin-left: auto; margin-right: auto; ">
				<td id="{{$work->id}}_item" class="collapse list-group" style="margin-left: auto; margin-right: auto; ">{{$work->comments[0]->comment}}</td>
			</tr>
		</tbody>
		@endforeach
	</table>
</div>
@endif
@else
<style>
	.title {
        font-size: 72px;
        margin-bottom: 40px;
    }
</style>
<div class="title">Looks like everything's in order</div>
@endif
</div>
</div>
@endsection