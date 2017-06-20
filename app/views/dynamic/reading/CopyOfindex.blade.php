@extends('layouts.home')

@section('content')
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-book fa-fw"></i> The Reading Center
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body text-center">
                    <div class="col-lg-6">
                    The Reading Center is similar to other e-readers, but paced to help you read faster. Select from our library of condensed classics of the World's Greatest Books or pick a biography about one of the World's Greatest People.<br><br>
                    You can select the reading speed and number of highlighted lines you want. For the best results, we recommend selecting a speed just beyond where you can keep up.<br><br><em>For the best experience, we recommend using the Reading Center on a desktop, laptop, or tablet device.</em><br><br>
                    </div>
                    <div class="col-lg-6">
                    <p style="font-weight:bold; color:#555;">Select your reading material from the options below.</p>
                    <br>
                    <label>Choose Category:</label><br>
                    <select id="category" name="category"  class="selectpicker form-control" data-width="auto">
                        @foreach ($categories as $category)
                            <option value='{{ $category->id }}'>{{ $category->name }}</option>
                        @endforeach
                    </select>
					<br />
                    <br>
                    <label>Choose Book:</label><br>
                    <select id="book" name="book" class="selectpicker form-control" data-live-search="true" data-width="auto" data-size:"10">
                    </select>
					<br />
                    <input type="submit" class="btn btn-success" onclick=" return validateChoice();" value="Start Reading" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    function loadBook()
    {
        var category = $("#category").val();
        if (category=="")
            return false;
        $("#book").html("<option value=''>Loading...</option>");
		$('#book').selectpicker('refresh');
        $.ajax({
            type: 'POST',
            url: "/{{ $url }}/reading/books",
            data: "cat_id=" + category,
            success: function(data){
                if (data.length>1)
                {
                    $("#book").html("");
                    for (var i=0;i<data.length;i++)
                    {
						$("#book").append("<option value='"+data[i].id+"'>"+data[i].book_title+"</option>");
                    }
                }
                else
                    $("#book").html("<option value=''>Records not found.</option>");
				$('#book').selectpicker('refresh');
			},
            dataType: "json"
        });
    }

    $("#category").change(function(){
        loadBook();
    });

    loadBook();
});

function validateChoice()
{
    if(!$("#category").val())
    {
        alert("Please select a reading category.");
        $("#category").focus();
        return false;
    }
    if(!$("#book").val())
    {
        alert("Please select a book.");
        $("#book").focus();
        return false;
    }
    else // redirect
    {
        var url=$("#book").val();
        window.location.href="/{{ $url }}/reading/"+url;
    }
}
</script>
@stop