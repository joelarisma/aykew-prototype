@extends('layouts.home')

@section('content')
<style>
#mainDiv, #mainDivDynamic{
	font-family: Arial;
	padding: 0px 25px;
	height:300px;
	overflow-y: hidden;
	line-height:25px;
	color: #C0C0C0;
	max-width: 670px;
	margin:auto;
	text-align: left;
}

.reading_center_uprContainer a:hover #bookmark-this
{
    color: #5cb85c !important;
}

@media all and (max-width: 549px) {
    #mainDiv, #mainDivDynamic {
        height:600px;
        padding: 0px 10px;
    }
}
</style>



<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-book fa-fw"></i> The Reading Center
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body text-center">
                    <div class="reading_center_greybox">
                        <div class="reading_row_grey clearfix">
                            <div class="reading_center_uprContainer">
                                {{--<div class="reading_center_pannel_div">--}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h1 style="font-size:14px; color:#666; text-transform:uppercase">
                                                        {{ $book->book_title }}
                                                    @if ($book->category_id != 0)
                                                        / {{ $book->category->name }}
                                                    @endif
                                                    </h1>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="col-md-2 col-md-offset-2">
                                                        <i class="fa fa-tachometer fa-2x" aria-hidden="true" style="color: #666 !important;"></i>
                                                        <br> <span style="font-size: 10px; color: #666;">Speed:</span>
                                                        <a class="label label-info popoverlink" data-toggle="popup" style="background-color: transparent;" data-content="The reading speed is the number of words per minute the system will enable you to read at for the material selected.">
                                                            <i class="fa fa-question-circle" aria-hidden="true" style="color: #5bc0de; font-size: 14px;"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select id="wpm_speed" class="selectpicker" data-width="auto">
                                                            <option value="100">100</option>
                                                            <option value="200">200</option>
                                                            <option value="300">300</option>
                                                            <option value="400">400</option>
                                                            <option value="500">500</option>
                                                            <option value="600" selected="selected">600</option>
                                                            <option value="700">700</option>
                                                            <option value="800">800</option>
                                                            <option value="900">900</option>
                                                            <option value="1000">1000</option>
                                                            <option value="1250">1250</option>
                                                            <option value="1500">1500</option>
                                                            <option value="1750">1750</option>
                                                            <option value="2000">2000</option>
                                                            <option value="2250">2250</option>
                                                            <option value="2500">2500</option>
                                                            <option value="2750">2750</option>
                                                            <option value="3000">3000</option>
                                                            <option value="3500">3500</option>
                                                            <option value="4000">4000</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <i class="fa fa-file-text-o fa-2x"  aria-hidden="true" style="color: #666;"></i>
                                                        <br> <span style="font-size: 10px; color: #666;">Block size:</span>
                                                        <a class="label label-info popoverlink" data-toggle="popup" style="background-color: transparent;" data-content="The block size is the number of highlighted lines you will be focusing on as you read the material selected.">
                                                            <i class="fa fa-question-circle" aria-hidden="true" style="color: #5bc0de; font-size: 14px;"></i>
                                                        </a>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <select id="block_size" class="selectpicker" data-width="auto">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        </select>
                                                    </div>



                                                    <div class="col-md-2">
                                                      <a href="" style="color:black;"> <i class="fa fa-bookmark-o fa-2x" id="bookmark-this" aria-hidden="true" style="color: #666;"></i></a>
                                                        <br> <span style="font-size: 10px; color: #666;">Bookmark:</span>
                                                        <a class="label label-info popoverlink" data-toggle="popup" style="background-color: transparent;" data-content="Click on the bookmark icon to bookmark your current reading location.">
                                                            <i class="fa fa-question-circle" aria-hidden="true" style="color: #5bc0de; font-size: 14px;"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                   <hr style=" border: 1px solid #777373;margin-bottom: 20px;">
                    <div class="reading_slider_content_section" id="reading_align" book-id="{{ $book->id }}" data-date="{{ \Carbon\Carbon::now() }}" user-book-activity="">
                        <div id="mainDiv" style="display:none;">{{ $book->description }}</div>
                        <div id="mainDivDynamic"></div>
                        <div class="reading_contentbox" id="reading-data" style="display:none"></div>
                        <br>
                        <div class="reading_timer_leftSec">
                            <input type="button" name="pause"  class="btn btn-success" id="btnstart2"  value="Start Reading" style="margin:0;" />
                            <input type="button" name="pause"  class="btn btn-default" id="pause2"  value="Pause" style="margin:0;display: none;" />
                            <input type="button" name="resume"  class="btn btn-success" id="resume2"  value="Resume" style="margin:0; display: none;" />
                        </div>
                    </div>
                    <div class="messagebox" style="display:none;"></div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookmark_Confirm" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button style="display:none" type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <p>It looks like you have a bookmark active for this selection. Would you like to resume reading or start from the beginning?</p>
            </div>
            <div style="padding:15px; text-align:right">
                <button id="start_over" class="btn btn-default">Start Over </button>
                <button id="resume" class="btn btn-success">Resume </button>
            </div>
        </div>

    </div>
</div>

<script src="/js/jquery.cookie.js"></script>
<script src="/js/reading-paced.js"></script>
<script>
</script>

<script>
    bookmarkWord='';
     article_id = '{{ $book->id }}';
    var book_category_id = '{{ $book->book_cat_id }}';
    var type = '{{ $type }}';

    $(function() {
        $('a').popover({
            trigger: 'hover',
            html: true,
            placement: 'bottom',
            container: 'body'
        });
        @if($bookmark != '')
            $('#bookmark_Confirm').modal('show');
        @endif

        addBookActivity();

    });

    jQuery(document).ready(function($) {

        $('#resume').click(function(){
            @if($bookmark != '')
                bookmarkWord = '{{ $bookmark->words }}'
            @endif
            getWords(charPerLine[columnSelectedValue],columnSelectedValue);
            $('#bookmark_Confirm').modal('hide');
            //$('#btnstart,#btnstart2').trigger('click');
        });

        $('#start_over').click(function(){
            bookmarkWord = '';
            $('#bookmark_Confirm').modal('hide');
            //$('#btnstart,#btnstart2').trigger('click');
        });
	
        $('#bookmark-this').click(function(e) {
	    e.preventDefault();
	    
            // Pause reading
            $("#pause2").trigger("click");

            var id = $(this);
            $(this).css('color','#00c2df');

            count = 0;

            if(heighLightedRow != "") {
                $.each($('#mainDivDynamic span'), function(index, span) {
                    var words = $(span).html().replace("<br>", "").length;

                    count += words;

                    if(span.id == heighLightedRow) {
                        return false
                    }
                });
            }

            // var count = heighLightedRow * charPerLine[columnSelectedValue];

            var delay=5000; // remove class after 5 second

            setTimeout(function() {
                id.css('color','#666');
            }, delay);

            count = count - 1;

            if(count == 0){
                alert('Please start reading first');
                return false;
            }

            $.ajax({
                url: '{{ url('reading/add-bookmark') }}',
                method: 'POST',
                data: {
                    book_id: article_id,
                    book_category_id: book_category_id,
                    type: type,
                    count: count
                },
                success:function(response){
		    //console.log(response);
                }
            });
        });
    });
    function addBookActivity() {
        var book_id = $('#reading_align').attr('book-id');
        var dateTime = $('#reading_align').attr('data-date');
        console.log("hello"+book_id);
        var url = '/add-book-activity';
        $.ajax({
            method: 'post',
            url: url,
            dataType: 'json',
            data: {
                book_id:book_id,
                dateTime:dateTime
            },
            success:function(data){
                $('#reading_align').attr('user-book-activity',data.id);
                console.log('Data successfully added');
            }
        });
    }
</script>
@stop
