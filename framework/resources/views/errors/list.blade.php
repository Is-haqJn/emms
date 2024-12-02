@if (count($errors) > 0)
	<div class="row">
        <div class="col-md-8">
			<div class="alert alert-danger">
			    <ul class=" mb-0">
			    	@foreach ($errors->all() as $error)
		        		<li>{{ $error }}</li>
		    		@endforeach
			    </ul>
			</div>
		</div>
	</div>
@endif