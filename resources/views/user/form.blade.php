{{-- <link rel="stylesheet" href="{{ asset('admin_remark_base/') }}/global/vendor/select2/select2.css"> --}}
<div class="modal-dialog modal-simple">
  {{ Form::model($user,array('route' => array((!$user->exists) ? 'user.store':'user.update',$user->pk()),
  'class'=>'modal-content','id'=>'user-form','method'=>(!$user->exists) ? 'POST' : 'PUT')) }}
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title" id="formModalLabel">{{ ($user->exists?'Edit':'Tambah').' Users' }}</h4>
  </div>
  <div class="modal-body">
    <div class="form-group row" style="margin-bottom: 1.429rem;">
      <label class="col-form-label col-md-3">Nama</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="nama" name="nama" value="{{($user->exists)?isset($user->name)?$user->name:'':''}}" required>
        <span class="help-block" id="nama_a"></span>
      </div>
    </div>
     <div class="form-group row" style="margin-bottom: 1.429rem;">
      <label class="col-form-label col-md-3">Username</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="username" name="username" value="{{($user->exists)?isset($user->username)?$user->username:'':''}}">
        <span class="help-block" id="username_a"></span>
      </div>
      <div class="col-md-2 float-left">
        <div class="text-left">
        <span class="help-block" id="message"></span>
      </div>
      </div>
    </div>
     <div class="form-group row" style="margin-bottom: 1.429rem;">
      <label class="col-form-label col-md-3">Email</label>
      <div class="col-md-7">
        <input type="email" class="form-control" id="email" name="email" value="{{($user->exists)?isset($user->email)?$user->email:'':''}}">
        <span class="help-block" id="email_a"></span>
      </div>
      <div class="col-md-2 float-left">
        <div class="text-left">
        <span class="help-block" id="message1"></span>
      </div>
      </div>
    </div>
    <div class="form-group row" style="margin-bottom: 1.429rem;">
      <label class="col-form-label col-md-3">Password</label>
      <div class="col-md-7">
        <input type="password" class="form-control" id="password" name="password" value="">
        <span class="help-block" id="password_a"></span>
      </div>
    </div>
    {{-- {!! App\Console\Commands\Generator\Form::input('name','text')->model($user)->show(['label'=>'Nama']) !!} --}}
    {{-- {!! App\Console\Commands\Generator\Form::input('username','text')->model($user)->show() !!} --}}
    {{-- {!! App\Console\Commands\Generator\Form::input('email','text')->model($user)->show() !!} --}}
    {{-- {!! App\Console\Commands\Generator\Form::input('password','password')->model($user)->show() !!} --}}
    {{-- {!! App\Console\Commands\Generator\Form::autocomplete('role',array('value'=>$user->exists?($user->display_name):null))->model(null)->show() !!} --}}
    {{-- {!! App\Console\Commands\Generator\Form::input('role_id','hidden')->model($user)->showHidden() !!} --}}
    <div class="form-group row" style="margin-bottom: 1.429rem;">
      <label class="col-form-label col-md-3">Role</label>
      <div class="col-md-7">
        <select class="form-control" id="role_id" name="role_id" data-plugin="select2">
        <optgroup label="Role">
          @if(!$user->exists)
          @foreach($role as $a)
          @if($a->display_name=='Karyawan')
          <option value="{{$a->id}}" selected>{{$a->display_name}}</option>
          @else
          <option value="{{$a->id}}">{{$a->display_name}}</option>
          @endif
          @endforeach
          @else
          @foreach($role as $a)
          @if($a->id==$user->role_id)
          <option value="{{$a->id}}" selected>{{$a->display_name}}</option>
          @else
          <option value="{{$a->id}}">{{$a->display_name}}</option>
          @endif
          @endforeach
          @endif
        </optgroup>
      </select>
      <span class="help-block" id="role_id_a"></span>
    </div>
    <input type="text" id="cek_nama" style="display: none" value="">
    <input type="text" id="cek_username" style="display: none" value="">
    <input type="text" id="cek_email" style="display: none" value="">
    <input type="text" id="cek_password" style="display: none" value="">
    <input type="text" id="cek_role" style="display: none" value="">
    <input type="text" id="id_user" style="display: none" value="{{!empty($user->pk())?$user->pk():''}}">
    <input type="text" id="mode" style="display: none" value="{{(!$user->exists) ? 'store':'update'}}">
  </div> 

    <div class="col-md-12 float-right">
      <div class="text-right">
        <button class="btn btn-primary" id="simpan">Simpan</button>
      </div>
    </div>
  </div>
  {{ Form::close() }}
</div>
{{-- <script src="{{ asset('admin_remark_base/') }}/global/vendor/select2/select2.full.min.js"></script>
<script src="{{ asset('admin_remark_base/') }}/global/js/Plugin/select2.js"></script> --}}
<script>
    $("#role_id").select2({
    dropdownParent: $("#user-form"),
    width: '100%'
  });

    $(document).ready(function(){
      $("#user-form").find("#nama").keyup(function(){
        if (!$.trim($(this).val())) {
          console.log('alert1');
      // alert('Country Name is required!');
      $('#nama_a').fadeIn();
      $("#nama_a").html("Nama Wajib Diisi");
      $("#nama_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $(this).css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      // return false;
    }else{
      $('#nama_a').fadeOut();
      $(this).css("border",'');
      $('#simpan').attr('disabled',false);
    }  
  });

      $("#user-form").find("#username").on('keyup',function(){
        if (!$.trim($(this).val())) {
          console.log('alert1');
      // alert('Country Name is required!');
      $('#username_a').fadeIn();
      $("#username_a").html("Username Wajib Diisi");
      $("#username_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $(this).css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid"});
      $('#simpan').attr('disabled',true);
      // return false;
    }else{
      //  console.log('baru');
      $('#username_a').fadeOut();
      $('#username').css("border",'');
      $('#simpan').attr('disabled',false);
      $('#message').fadeOut();
    }  
    // $("input").css("background-color", "pink");
  });

      // $("#user-form").find("#username").on('change',function(){
      //   $('#message').fadeIn();
      //   $("#message").html("<img src='{{asset('images/') }}/ajax-loader.gif' /> Memeriksa...");

      //   $.getJSON('{{ url('pegawai/cek-username-type1/') }}', {username: $('#username').val()}, function(json, textStatus) {
      //   // console.log(json);
      //   // $.each(json,function(index, el) {
      //     // console.log(el);
      //     if(json.status==true)
      //     {
      //       $("#message").html("<img src='{{asset('images/') }}/cross.png' width='20pt' height='25pt'/>");
      //       $('#username_a').fadeIn();
      //       $("#username_a").html("Username Sudah Digunakan User Lain");
      //       $("#username_a").css({"color":"red",
      //         "font-size":"12px",
      //         "font-family":"Roboto, sans-serif"});
      //       $('#username').css({"border-color": "red", 
      //         "border-width":"1px", 
      //         "border-style":"solid"});
      //     }
      //     else if(json.status==false){
      //       $("#message").html("<img src='{{asset('images/') }}/tick.png' width='20pt' height='25pt' />");
      //       $('#username_a').fadeIn();
      //       $("#username_a").html("Username Tersedia");
      //       $("#username_a").css({"color":"green",
      //         "font-size":"12px",
      //         "font-family":"Roboto, sans-serif"});
      //       $('#username').css({"border-color": "green", 
      //         "border-width":"1px", 
      //         "border-style":"solid"});

      //       $('#username_a').delay(2000).fadeOut();
      //       $('#simpan').attr('disabled',false);
      //       $('#message').delay(2000).fadeOut();
      //       $('#username').delay(3000).css("border",'');

      //     }
      //   // });
      // })
      // });

      $("#user-form").find("#email").on('keyup',function(){
        if (!$.trim($(this).val())) {
          console.log('alert1');
          $('#email_a').fadeIn();
          $("#email_a").html("Email Wajib Diisi");
          $("#email_a").css({"color":"red",
            "font-size":"12px",
            "font-family":"Roboto, sans-serif"});
          $(this).css({"border-color": "red", 
            "border-width":"1px", 
            "border-style":"solid"});
          $('#simpan').attr('disabled',true);
      // return false;
    }else if(!isEmail($('#email').val()))
    {
      $('#email_a').fadeIn();
      $("#email_a").html("Format email example@example.com");
      $("#email_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $(this).css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid"});
      $('#simpan').attr('disabled',true);
    }
    else{
      $('#email_a').fadeOut();
      $('#email').css("border",'');
      $('#simpan').attr('disabled',false);
      $('#message1').fadeOut();
      //console.log('baru');
    }
  });

      // $("#user-form").find("#email").on('change',function(){
      //   $('#message1').fadeIn();
      //   $("#message1").html("<img src='{{asset('images/') }}/ajax-loader.gif' /> Memeriksa...");

      //   $.getJSON('{{ url('pegawai/cek-email-type1/') }}', {email: $('#email').val()}, function(json, textStatus) {
      //   // console.log(json);
      //   // $.each(json,function(index, el) {
      //     // console.log(el);
      //     if(json.status==true)
      //     {
      //       $("#message1").html("<img src='{{asset('images/') }}/cross.png' width='20pt' height='25pt'/>");
      //       $('#email_a').fadeIn();
      //       $("#email_a").html("Email Sudah Digunakan User Lain");
      //       $("#email_a").css({"color":"red",
      //         "font-size":"12px",
      //         "font-family":"Roboto, sans-serif"});
      //       $('#email').css({"border-color": "red", 
      //         "border-width":"1px", 
      //         "border-style":"solid"});
      //       $('#simpan').attr('disabled',true);
      //     }
      //     else if(json.status==false){
      //       $("#message1").html("<img src='{{asset('images/') }}/tick.png' width='20pt' height='25pt' />");
      //       $('#email_a').fadeIn();
      //       $("#email_a").html("Email Tersedia");
      //       $("#email_a").css({"color":"green",
      //         "font-size":"12px",
      //         "font-family":"Roboto, sans-serif"});
      //       $('#email').css({"border-color": "green", 
      //         "border-width":"1px", 
      //         "border-style":"solid"});

      //       $('#email_a').delay(2000).fadeOut();
      //       $('#simpan').attr('disabled',false);
      //       $('#message1').delay(2000).fadeOut();
      //       $('#email').delay(3000).css("border",'');

      //     }
      //   // });
      // })
      // });

      $("#user-form").find("#password").keyup(function(){
        if (!$.trim($(this).val())) {
          console.log('alert1');
      // alert('Country Name is required!');
      $('#password_a').fadeIn();
      $("#password_a").html("Password Wajib Diisi");
      $("#password_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $(this).css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      // return false;
    }else{
      if($(this).val().length<8)
      {
        $('#password_a').fadeIn();
        $("#password_a").html("Password minimal 8 karakter");
        $("#password_a").css({"color":"red",
          "font-size":"12px",
          "font-family":"Roboto, sans-serif"});
        $(this).css({"border-color": "red", 
          "border-width":"1px", 
          "border-style":"solid",
        });
        $('#simpan').attr('disabled',true);
      }
      else
      {
        $('#password_a').fadeOut();
        $(this).css("border",'');
        $('#simpan').attr('disabled',false);
      }
      
    }  
  });

      $("#user-form").find("#role_id").change(function(){
        if (!$.trim($(this).val())) {
          console.log('alert1');
      // alert('Country Name is required!');
      $('#role_id_a').fadeIn();
      $("#role_id_a").html("Role Wajib Diisi");
      $("#role_id_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $(this).css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      // return false;
    }else{
      $('#role_id_a').fadeOut();
      $(this).css("border",'');
      $('#simpan').attr('disabled',false);
    }  
    // $("input").css("background-color", "pink");
  });

  $('#user-form').submit('#simpan',function(e){
    if($('#user-form').find('#nama').val()=='')
    {
      e.preventDefault();
       $('#nama_a').fadeIn();
      $("#nama_a").html("Nama Wajib Diisi");
      $("#nama_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $('#nama').css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      $('#cek_nama').val('true');
       unbind_simpan();
    }
    else{
      $('#cek_nama').val('false');
       unbind_simpan();
    }
    if($('#user-form').find('#username').val()=='')
    {
      e.preventDefault();
       $('#username_a').fadeIn();
      $("#username_a").html("Username Wajib Diisi");
      $("#username_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $('#username').css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      $('#cek_username').val('true');
       unbind_simpan();

    }
    /*else{
       e.preventDefault();
      $('#message').fadeIn();
        $("#message").html("<img src='{{asset('images/') }}/ajax-loader.gif' /> Memeriksa...");

        $.getJSON('{{ url('user/cek-username/') }}', {username: $('#username').val(),status:$('#mode').val(),id_user:$('#id_user').val()}, function(json, textStatus) {
        // console.log(json);
        // $.each(json,function(index, el) {
          // console.log(el);
          if(json.status==true)
          {
            $("#message").html("<img src='{{asset('images/') }}/cross.png' width='20pt' height='25pt'/>");
            $('#username_a').fadeIn();
            $("#username_a").html("Username Sudah Digunakan User Lain");
            $("#username_a").css({"color":"red",
              "font-size":"12px",
              "font-family":"Roboto, sans-serif"});
            $('#username').css({"border-color": "red", 
              "border-width":"1px", 
              "border-style":"solid"});
            $('#simpan').attr('disabled',true);
            $('#cek_username').val('true');
            unbind_simpan();
            return false;
          }
          else if(json.status==false){
            $("#message").html("<img src='{{asset('images/') }}/tick.png' width='20pt' height='25pt' />");
            $('#username_a').fadeIn();
            $("#username_a").html("Username Tersedia");
            $("#username_a").css({"color":"green",
              "font-size":"12px",
              "font-family":"Roboto, sans-serif"});
            $('#username').css({"border-color": "green", 
              "border-width":"1px", 
              "border-style":"solid"});

            $('#username_a').delay(2000).fadeOut();
            $('#simpan').attr('disabled',false);
            $('#message').delay(2000).fadeOut();
            $('#username').delay(3000).css("border",'');
            $('#cek_username').val('false');
            unbind_simpan();
            // $('#user-form').unbind('submit').submit();
          }
        // });
      })
    } */

    if($('#user-form').find('#email').val()=='')
    {
      e.preventDefault();
       $('#email_a').fadeIn();
      $("#email_a").html("Email Wajib Diisi");
      $("#email_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $('#email').css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      $('#cek_email').val('true');
       unbind_simpan();
    }
    /*else{
      e.preventDefault();
      $('#message1').fadeIn();
        $("#message1").html("<img src='{{asset('images/') }}/ajax-loader.gif' /> Memeriksa...");

        $.getJSON('{{ url('user/cek-email/') }}', {email: $('#email').val(),status:$('#mode').val(),id_user:$('#id_user').val()}, function(json, textStatus) {
        // console.log(json);
        // $.each(json,function(index, el) {
          // console.log(el);
          if(json.status==true)
          {
            $("#message1").html("<img src='{{asset('images/') }}/cross.png' width='20pt' height='25pt'/>");
            $('#email_a').fadeIn();
            $("#email_a").html("Email Sudah Digunakan User Lain");
            $("#email_a").css({"color":"red",
              "font-size":"12px",
              "font-family":"Roboto, sans-serif"});
            $('#email').css({"border-color": "red", 
              "border-width":"1px", 
              "border-style":"solid"});
            $('#simpan').attr('disabled',true);
            $('#cek_email').val('true');
            unbind_simpan();
            return false;
          }
          else if(json.status==false){
            $("#message1").html("<img src='{{asset('images/') }}/tick.png' width='20pt' height='25pt' />");
            $('#email_a').fadeIn();
            $("#email_a").html("Email Tersedia");
            $("#email_a").css({"color":"green",
              "font-size":"12px",
              "font-family":"Roboto, sans-serif"});
            $('#email').css({"border-color": "green", 
              "border-width":"1px", 
              "border-style":"solid"});

            $('#email_a').delay(2000).fadeOut();
            $('#simpan').attr('disabled',false);
            $('#message1').delay(2000).fadeOut();
            $('#email').delay(3000).css("border",'');
            $('#cek_email').val('false');
            unbind_simpan();
            // $('#user-form').unbind('submit').submit();
          }
        // });
      })
    } */

    if($('#mode').val()=='store')
    {
      if($('#user-form').find('#password').val()=='')
      {
        e.preventDefault();
        $('#password_a').fadeIn();
        $("#password_a").html("Password Wajib Diisi");
        $("#password_a").css({"color":"red",
          "font-size":"12px",
          "font-family":"Roboto, sans-serif"});
        $('#password').css({"border-color": "red", 
          "border-width":"1px", 
          "border-style":"solid",
        });
        $('#simpan').attr('disabled',true);
        $('#cek_password').val('true');
        unbind_simpan();

      }else{
        $('#cek_password').val('false');
        unbind_simpan();
      }
    }

    if($('#user-form').find('#role_id').val()=='')
    {
      e.preventDefault();
       $('#role_id_a').fadeIn();
      $("#role_id_a").html("Role Wajib Diisi");
      $("#role_id_a").css({"color":"red",
        "font-size":"12px",
        "font-family":"Roboto, sans-serif"});
      $('#role_id').css({"border-color": "red", 
        "border-width":"1px", 
        "border-style":"solid",
      });
      $('#simpan').attr('disabled',true);
      $('#cek_role').val('true');
       unbind_simpan();
    }
    else{
      $('#cek_role').val('false');
       unbind_simpan();
    }
  })

    })

//   $('#user-form').formValidation({
//     framework: "bootstrap4",
//     button: {
//       selector: '#simpan',
//       disabled: 'disabled'
//     },
//     icon: null,
//     fields: {
//       name: {
//         validators: {
//           notEmpty: {
//             message: 'The full name is required and cannot be empty'
//           }
//         }
//       },
//       email: {
//         validators: {
//           notEmpty: {
//             message: 'The email address is required and cannot be empty'
//           },
//           emailAddress: {
//             message: 'The email address is not valid'
//           }
//         }
//       },
//       password: {
//         validators: {
//           notEmpty: {
//             message: 'The password is required'
//           },
//           stringLength: {
//             min: 8,
//             message: 'The content must be less than 8 characters long'
//           }
//         }
//       },
//       username: {
//         validators: {
//           notEmpty: {
//             message: 'The content is required and cannot be empty'
//           },
//           stringLength: {
//             max: 50,
//             message: 'The content must be less than 50 characters long'
//           }
//         }
//       },
//       id_role: {
//         validators: {
//           notEmpty: {
//             message: 'The content is required and cannot be empty'
//           },
//         }
//       },
//     },
//     err: {
//       clazz: 'invalid-feedback'
//     },
//     control: {
//     // The CSS class for valid control
//     valid: 'is-valid',

//     // The CSS class for invalid control
//     invalid: 'is-invalid'
//   },
//   row: {
//     invalid: 'has-danger'
//   }
// });
  // var state = new Bloodhound({
  //   datumTokenizer: Bloodhound.tokenizers.whitespace,
  //   queryTokenizer: Bloodhound.tokenizers.whitespace,
  //   cache: false,
  //   local: states
  // });

  function unbind_simpan()
  {
    // console.log(a);
    // console.log(b);
    if($('#mode').val()=='store')
    {
     if($('#cek_nama').val()=='false' && $('#cek_email').val()==='false' && $('#cek_username').val()==='false' && $('#cek_password').val()=='false' && $('#cek_role').val()=='false')
     {
      console.log('aaaaa');
      // $('#user-form').unbind('submit').submit();
      var route;
      if($('#mode').val()=='store')
      {
        route='{{route('user.store')}}';
      }
      else
      {
        console.log('aaaa');
        route='{{url('user/update/'.$user->pk())}}';
      }

      $.ajax({
        url : route,
        type: "POST",
        data: $('#user-form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

          if(data.status==true)
          {
        // $("#kelompok").attr('col-md-12','col-md-7');
        notification(''+data.message+'','success');
        $('#formModal').modal('hide');
        reload_table();
        
        
        
        // var id_p=data.id['id'];
        
        // var id_p=data.id;
        // return id_p
        // get_id(data.id);
        
        
      }
      else if(data.status==false)
      {
        notification(''+data.message+'','error');
        // setTimeout(function(){
        //         window.location.href="{{url('pembayaran')}}"
        //       },1000);

      }

    },
    error: function (jqXHR, textStatus, errorThrown)
    {
            // alert('Error adding / update data');
            // $('#btnSave').text('save'); //change button text
            // $('#btnSave').attr('disabled',false); //set button enable 

          }
        });
    }
    else
    {
      return false;
    }
  }else
  {
   if($('#cek_nama').val()=='false' && $('#cek_email').val()==='false' && $('#cek_username').val()==='false' && $('#cek_role').val()=='false')
   {
    console.log('aaaaa');
      // $('#user-form').unbind('submit').submit();
      var route;
      if($('#mode').val()=='store')
      {
        route='{{route('user.store')}}';
      }
      else
      {
        console.log('aaaa');
        route='{{url('user/update/'.$user->pk())}}';
      }

      $.ajax({
        url : route,
        type: "GET",
        data: $('#user-form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

          if(data.status==true)
          {
        // $("#kelompok").attr('col-md-12','col-md-7');
        notification(''+data.message+'','success');
        $('#formModal').modal('hide');
        reload_table();
        
        
        
        // var id_p=data.id['id'];
        
        // var id_p=data.id;
        // return id_p
        // get_id(data.id);
        
        
      }
      else if(data.status==false)
      {
        notification(''+data.message+'','error');
        // setTimeout(function(){
        //         window.location.href="{{url('pembayaran')}}"
        //       },1000);

      }

    },
    error: function (jqXHR, textStatus, errorThrown)
    {
            // alert('Error adding / update data');
            // $('#btnSave').text('save'); //change button text
            // $('#btnSave').attr('disabled',false); //set button enable 

          }
        });
    }
    else
    {
      return false;
    }
  }

}

  var roleEngine = new Bloodhound({
    datumTokenizer: function(d) { return d.tokens; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    cache: false,
    remote: {
      url: '{{ url("autocomplete/role") }}?q=%QUERY',
      wildcard: "%QUERY"
    }
  });

  // passing in `null` for the `options` arguments will result in the default
  // options being used
  $('#role').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  },
  {
    name: 'role',
    displayKey: 'display_name',
    source: roleEngine.ttAdapter(),
    templates: {
      suggestion: function(data){
        return Handlebars.compile([
                  // "<div class=\"tt-menu\">",
                  "<div class=\"tt-dataset\">",
                  "<div>@{{display_name}}</div>",
                  "</div>",
                  // "</div>",
                  ].join(""))(data);
                },
                empty: [
                "<div>role tidak ditemukan</div>"
                ]
              }
            }).bind("typeahead:selected", function(obj, datum, name) {
              $("#role_id").val(datum.id);
            }).bind("typeahead:change", function(obj, datum, name) {

            });

            function isEmail(email) {
              var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              return regex.test(email);
            }
          </script>
