   <form role="form" class="form-edit-add" {{ $attributes }} method="{{ $method }}"
       enctype="multipart/form-data">
       @csrf
       @if ($isPut == 'si')
           @method('PUT')
       @endif


       <div class="panel-body">


           <!-- Adding / Editing -->

           <!-- GET THE DISPLAY OPTIONS -->
           {{ $slot }}

       </div><!-- panel-body -->

       <div class="panel-footer">
           <button type="submit" class="btn btn-primary save">{{ $btntext }}</button>
       </div>
   </form>
