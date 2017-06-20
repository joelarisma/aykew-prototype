@extends('layouts.home')

{{-- PAGE-LEVEL STYLES --}}
@section("style")

@stop

{{-- PAGE-LEVEL CONTENT --}}
@section('content')
<div id="page-wrapper" class="dynamic-reading">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                    <i class="fa fa-book fa-fw"></i>
                    The Reading Center
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            The Reading Center is similar to other e-readers, 
                            but paced to help you read at higher speeds by 
                            selecting the number of lines you wish to read at a 
                            time in your desired or last reading speed.  
                            Attempt to read one book a week and the see the 
                            difference! Select from our library of condensed 
                            classics and reading material, or upload your own. 
                            You can then select the reading speed, the number 
                            of highlighted lines to view each time and begin 
                            reading! You can even bookmark your reading and 
                            come back the next day to finish your reading. 
                            <em>For the best experience, we recommend using the 
                            Reading Center on a desktop, laptop, or tablet 
                            device.</em>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 col-md-5 col-md-offset-1">
                            <div class="heading">
                                <h4>Option 1: Select reading material</h4>
                            </div>

                            <!-- BROWSE OUR LIBRARY -->
                            <div class="text-center">
                                <div class="title">
                                    Browse our library
                                </div>
                                <div class="body">
                                    <p>
                                        Select your reading material from the 
                                        options below.
                                    </p>
                                    
                                    <!-- Choose Category -->
                                    <label>Choose Category:</label>
                                    <select id="eyeqCategory" class="selectpicker form-control" data-width="auto">
                                    @foreach ($categories as $category)
                                        <option value='{{ $category->id }}'>{{ $category->name }}</option>
                                    @endforeach
                                    </select>
                                    
                                    <!-- Choose Book -->
                                    <label>Choose Book:</label>
                                    <select id="eyeqBook" class="selectpicker form-control" data-live-search="true" data-width="auto"></select>
                                    
                                    <!-- Start Reading -->
                                    <button id="eyeqStartReading" class="btn btn-success">Start Reading</button>
                                </div>
                            </div>
                            
                            <!-- BROWSE YOUR OWN MATERIAL -->
                            <div class="text-center">
                                <div class="title">
                                    Browse your own material
                                </div>
                                <div class="body">
                                    <p>
                                        Select your reading material from the 
                                        options below.
                                    </p>
                                    
                                    <!-- Choose Book -->
                                    <label>Choose Book:</label>
                                    <select id="personal" class="selectpicker form-control" data-width="auto">
                                    @foreach ($personalBooks as $personalBook)
                                        <option value='{{ $personalBook->id }}'>{{ $personalBook->book_title }}</option>
                                    @endforeach
                                    </select>
                                    
                                    <!-- Start Reading -->
                                    <button id="personalStartReading" class="btn btn-success">Start Reading</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-5">
                            <div class="heading">
                                <h4>Option 2: Upload your own material</h4>
                                <span class="btn">NEW!</span>
                            </div>
                            
                            <!-- UPLOAD YOUR OWN MATERIAL -->
                            <div class="text-center">
                                <div class="title">
                                    Upload and read your own material
                                </div>
                                <div class="body">
                                    <form action="jquery_ajax" class="form-horizontal" id="bookForm">
                                        
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="title" placeholder="Title of reading material" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="image" required>
                                            <button class="btn btn-primary">Upload</button>
                                        </div>
                                        
                                        <!-- NOT FOR MONDAY
                                        <div class="dragDrop">
                                            Drag and drop files in this area
                                        </div>
                                        -->
                                    </form>
  
                                    <input type="hidden" name="lastUploadInput" id="lastUploadInput" value="0">
                                    <table id="uploadedBooks">
                                        <tbody>
                                            <tr>
                                                <td>No books uploaded</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <!-- Start Reading -->
                                    <button id="uploadStartReading" class="btn btn-success">Start Reading</button>
                                    
                                    <p class="fileTypeInfo">
                                        Compatible File Types: .txt, .doc, .docx, .mobi, and .epub files.
                                    </p>
                                </div>
                                <div class="overlay">
                                    <div class="notice">
                                        <p>
                                            <input type="checkbox" id="overlayCheckbox" required>
                                            I understand that Infinite Mind has no control over,
                                            and assumes no responsibility for the content I upload,
                                            post or distribute on eyeqadvantage.org. By using this service,
                                            I expressly relieve Infinite Mind from any and all liability with
                                            respect to the content I upload and the activities of its users with respect thereto.
                                        </p>
                                        <button id="overlayButton" class="btn btn-primary" disabled>Let me upload!</button>
                                    </div>
                                </div>
                            </div>
                            <div class="storage">
                                Your subscription <strong>allows {{ $storageLimit }} MB of storage.</strong>
                                You are <strong>currently using <span id="storageUsed">{{ round($storageUsed / 1000000, 2)}}</span> MB.</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

{{-- PAGE-LEVEL SCRIPTS --}}
@section("script")
{{ HTML::script('js/jquery.bootstrap-growl.min.js') }}

<script>
    /****************
     * GLOBAL SCRIPTS ----------------------------------------------------------
     ****************/

    // DOM READY
    $(document).ready(function() {

        // eyeQ ------------------------
        loadEyeQ();
        $("#eyeqCategory").on("change", function()      {loadEyeQ();});
        $("#eyeqStartReading").on("click", function()   {validateEyeQ();});

        // Personal --------------------
        $("#personalStartReading").on("click", function() {validatePersonal();});

        // Uploaded --------------------
        $("#uploadStartReading").on("click", function() {validateUpload();});

        // Overlay check ---------------
        $("#overlayCheckbox").on("click", function() {
            $("#overlayButton").prop("disabled", !this.checked);
        });

        // Overlay button --------------
        $("#overlayButton").on("click", function() {
            $(this).parent().parent().css("display", "none");
        });
        
        // Uploaded ---------------------
        $("#bookForm").submit(function(e) {
            uploadBook(e);
        });
    });

    /******************
     * GLOBAL FUNCTIONS --------------------------------------------------------
     ******************/

    /**
     * Populate book <select> with the selected category book names
     */
    function loadEyeQ() {

        // Grab category
        var category = $("#eyeqCategory").val();

        // gtfohur
        if (category == "") {
            return false;
        }

        // AJAX placeholder
        $("#eyeqBook").html("<option value=''>Loading...</option>");
        $('#eyeqBook').selectpicker('refresh');

        // POST to get book names
        $.ajax({
            type:   'POST',
            url:    "/{{ $url }}/reading/books",
            data:   "cat_id=" + category,
            success: function(data) {

                // Books found
                if (data.length > 0) {

                    // Clear options
                    $("#eyeqBook").html("");

                    // Rebuild options
                    for (var i = 0; i < data.length; i++) {
                        var option = $(document.createElement("option"));
                        option.val(data[i].id);
                        option.text(data[i].book_title);

                        // Add to select
                        $("#eyeqBook").append(option);
                    }
                }

                // No books found
                else {
                    $("#eyeqBook").html("<option value=''>Records not found.</option>");
                }

                // Rebuild select
                $('#eyeqBook').selectpicker('refresh');
            },
            dataType: "json"
        });
    }

    /**
     * Validate selects, and redirect to Reading
     */
    function validateEyeQ() {
        
        // No category chosen
        if(!$("#eyeqCategory").val()) {
            alert("Please select a category.");
            $("#eyeqCategory").focus();
            return false;
        }

        // No book chosen
        if(!$("#eyeqBook").val()) {
            alert("Please select a book.");
            $("#eyeqBook").focus();
            return false;
        }

        // Redirect
        else {
            var url = $("#eyeqBook").val();
            window.location.href = "/{{ $url }}/reading/" + url;
        }
    }

    function validatePersonal() {
        var url = $("#personal").val();
        window.location.href = "/{{ $url }}/reading/user/" + url;
    }

    function validateUpload() {
        var url = $("#lastUploadInput").val();

        if (url == 0) {
            alert("Please upload a book.");
            return false;
        }
         
        window.location.href = "/{{ $url }}/reading/user/" + url;
    }

    /**
     * Upload a book via AJAX
     * 
     * @param e JS submit event
     */
    function uploadBook(e) {

        // Prevent form submission
        e.preventDefault();

        // Convert form to FormData
        var form = e.target;
        var formData = new FormData(form);

        // POST to get book names
        $.ajax({
            type:           "post",
            url:            "/{{ $url }}/reading/upload",
            data:           formData,
            processData:    false,
            contentType:    false,
            success:        function(data, textStatus, jqXHR) {

                // Response data
                var status = data.status;
                var statusType = data.statusType;
                var filename = data.filename;
                var filesize = data.filesize;
                var storageUsed = data.storageUsed;
                var bookTitle = data.bookTitle;
                var bookID = data.bookID;

                // Server error
                if (status == "fail") {
                    if (statusType == "titleEmpty") {
                        $.bootstrapGrowl("Please enter a title", {type: "info"});
                    }
                    else if (statusType == "fileEmpty") {
                        $.bootstrapGrowl("Please upload a file", {type: "info"});
                    }
                    else if (statusType == "fileSize") {
                        $.bootstrapGrowl("File size exceeds maximum", {type: "danger"});
                    }
                    else if (statusType == "fileType") {
                        $.bootstrapGrowl("Please choose a valid file format", {type: "info"});
                    }
                    else if (statusType == "noStorageLeft") {
                        $.bootstrapGrowl("Storage limit exceeded", {type: "danger"});
                    }
                    else {
                        $.bootstrapGrowl("Error uploading file content", {type: "danger"});
                    }
                }

                // Good to go
                else {

                    // Table
                    var table = $("#uploadedBooks tbody");
    
                    // Clear default table
                    var count = table.find("td").length;
                    if (count == 1) {
                        table.html("");
                    }
    
                    // Add new row
                    var tr = $(document.createElement("tr"));
                    tr.appendTo(table);
    
                        // File title
                        var tdName = $(document.createElement("td"));
                        tdName.appendTo(tr);

                            // HTML in case of truncation
                            var tdSpan = $(document.createElement("span"));
                            tdSpan.addClass("truncate");
                            tdSpan.text(filename);
                            tdSpan.appendTo(tdName);
                            
                        tdName.append("&nbsp;is uploaded");
    
                        // File size
                        var filesizeMB = (filesize / 1000000).toFixed(2);
                        var tdSize = $(document.createElement("td"));
                        tdSize.text(filesizeMB + " MB"); 
                        tdSize.appendTo(tr);
    
                    // Update storage metrics
                    var storageUsedMB = (storageUsed / 1000000).toFixed(2);
                    $("#storageUsed").text(storageUsedMB);
    
                    // User feedback
                    $.bootstrapGrowl("File uploaded successfully", {type: "success"});

                    // Add new book to user's select list
                    var newBook = $(document.createElement("option"));
                    newBook.val(bookID);
                    newBook.text(bookTitle);
                    $("#personal").append(newBook);
                    $("#personal").selectpicker("refresh");
                    $("#lastUploadInput").val(bookID);
                }

                // Reset form
                form.reset();
            }
        });
    }
</script>
@stop
