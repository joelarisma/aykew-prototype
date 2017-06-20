@extends('layouts.home')

@section('content')
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-user fa-fw"></i> Edit Profile
                </div>
                <div class="panel-body text-center">
                <form name="updateProfileFrm" method="post">
                    <label>First Name: <span class="required">*</span></label><br/>
                    <input type="text" name="name" id="name" class="form-control" style="width:70%;margin:auto;text-align:center;" value="{{ $currentUser->name }}" placeholder="Name">
                    <br/><br/>
                    <label>Last Name: </label><br/>
                    <input type="text" name="last_name" id="last_name" class="form-control" style="width:70%;margin:auto;text-align:center;" value="{{ $currentUser->last_name }}" placeholder="Last Name">
                    <br/><br/>
                    <label>Email Address: <span class="required">*</span></label><br/>
                    <input type="text" name="email" class="form-control" id="email" style="width:70%;margin:auto;text-align:center;" value="{{ $currentUser->email }}" placeholder="Email Address">
                    <br><br>
                    <label>Reading Material: <span class="required">*</span></label><br/>
                    {{ Form::select("reading", $select_reading, $currentUser->level_id, ['class'=>'form-control', 'style'=>'width:70%;margin:auto']) }}
                    <br><br>
                    <label>Timezone: <span class="required">*</span></label><br>
                    {{ Form::select('tz', $select_tz, $currentUser->tz, ['class'=>'form-control', 'style'=>'width:70%;margin:auto']) }}
                    <br><br>
                    <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success">
                </form>
                <br><br>
                <a href="http://en.gravatar.com" target="_blank">Add or Edit your Avatar*</a>
                <p><small>(*We use global avatars from Gravatar.com)</small></p>
	        </div>
	    </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>
@stop

@section('script')
<script>
function is_email(email) {
	if(!email.match(/^[A-Za-z0-9\._\-+]+@[A-Za-z0-9_\-+]+(\.[A-Za-z0-9_\-+]+)+$/))
		return false;
	return true;
}

$(function(){
    $("#submit").on("click",function(e) {
        $(".error").remove();
        var emailReg =/^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
        var name = $("#name").val();
        var email = $("#email").val();
        $("#name").after('');

        if ($.trim(name)==''){
            $("#name").after('<br><span class="error">Please enter your name.</span>');
            $("#name").focus();
            return false;
        }

        if (email!='' && !emailReg.test(email)) {
            $("#email").after('<br><span class="error">Please enter a valid email address.")?></span>');
            $("#email").focus();
            return false;
        }
    });
});
</script>
@stop