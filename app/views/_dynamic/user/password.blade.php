@extends('layouts.home')

@section('content')
    <div id="page-wrapper">
        <div class="row" style="padding-top:15px;">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Change Password</div>

                    <div class="panel-body text-center">
                    @include('flash::message')
                        <form name="changePassFrm" method="post" onSubmit="return validatePassword();">
                            <label>Current password: <span class="required">*</span></label><br>
                            <input type="password" name="current_password" class="form-control" id="current_password" style="width:70%;margin:auto;text-align:center;" placeholder="Current password">
                            <br><br>
                            <label>New password: <span class="required">*</span></label><br>
                            <input type="password" name="new_password" class="form-control" id="new_password" style="width:70%;margin:auto;text-align:center;" placeholder="New password">
                            <br><br>
                            <label>Confirm new password: <span class="required">*</span></label><br>
                            <input type="password" name="cpassword" class="form-control" id="cpassword" style="width:70%;margin:auto;text-align:center;" placeholder="Confirm new password">
                            <br><br>
                            <input type="submit" name="submit" id="submit" value="Change Password" class="btn btn-success">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
@stop

@section('script')
<script>
function validatePassword()
{
    var f = document.changePassFrm;

    if(!f.current_password.value)
    {
        alert("Please enter your current password")
        f.current_password.focus();
        return false;
    }
    if(!f.new_password.value)
    {
        alert("Please enter a new password")
        f.new_password.focus();
        return false;
    }
    if(f.new_password.value.length<6)
    {
        alert("Password must be at least 6 characters long");
        f.new_password.focus();
        return false;
    }
    if(!f.cpassword.value)
    {
        alert("Please confirm your password")
        f.cpassword.focus();
        return false;
    }
    if((f.cpassword.value) != (f.new_password.value))
    {
        alert('The new password and the confirmation password do not match')
        f.cpassword.focus();
        return false;
    }
    else
        return true;
}

$(function() {
    $('#current_password').attr('placeholder', 'Current Password');
	$('#new_password').attr('placeholder', 'New Password');
	$('#cpassword').attr('placeholder', 'Confirm Password');
});
</script>
@stop