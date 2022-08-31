@if(count($errors))

<div class="form-group">
    <div class="alert alert-danger alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><i class="fa fa-warning"></i>&nbsp;
            @foreach ($errors->all() as $error)

            {{ $error }}<br>

            @endforeach
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->

@else 

    @if (session('formSuccess'))
        <div class="form-group">
            <div class="alert alert-success alert-dismissible" id="divMessage" >
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><i class="fa fa-check"></i>&nbsp;
                    {{ session('formSuccess') }}
                </p>
            </div> <!-- /.alert -->
        </div> <!-- /.form-group -->
    @endif

@endif