<!DOCTYPE html>
<html>
<head>
	<title>Progress Bar Demo</title>
	<script src="../../javascripts/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
		function _(el) {
			return document.getElementById(el);
		}

		/*
		$('#uploadFileBtn').click(function(event){
			var file = _("file1").files[0];
			alert(file.name+" | "+file.size+" | "+file.type);
			var formdata = new FormData();
			formdata.append("file1", file);

			var ajax = new XMLHttpRequest();
			// This is called anytime there is a new progress of upload ot the server
			// the ".upload" is to monitor the progress of the upload
			ajax.upload.addEventListener("progress", progressHandler, false);
			ajax.addEventListener("load", completeHandler, false);
			ajax.addEventListener("error", errorHandler, false);
			ajax.addEventListener("abort", abortHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
		});
		*/

		function uploadFile() {
			var file = _("file1").files[0];
			alert(file.name+" | "+file.size+" | "+file.type);
			var formdata = new FormData();
			formdata.append("file1", file);

			var ajax = new XMLHttpRequest();
			// This is called anytime there is a new progress of upload ot the server
			// the ".upload" is to monitor the progress of the upload
			ajax.upload.addEventListener("progress", progressHandler, false);
			ajax.addEventListener("load", completeHandler, false);
			ajax.addEventListener("error", errorHandler, false);
			ajax.addEventListener("abort", abortHandler, false);
			ajax.open("POST", "file_upload_parser.php");
			ajax.send(formdata);
		}

		function progressHandler(e) {
			// get the object of the paragraph on html
			_("loaded_n_total").innerHTML = "Uploaded "+e.loaded+" bytes of "+e.total;
			var percent = (e.loaded / e.total) * 100;
			_("progressBar").value = Math.round(percent);
			_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
		}

		function completeHandler(e) {
			// get a response from the php script after attempt to upload
			_("status").innerHTML = e.target.responseText;	
			_("progressBar").value = 0;			
		}

		function errorHandler(e) {
			_("status").innerHTML = "Upload Failed";	
		}

		function abortHandler(e) {
			_("status").innerHTML = "Upload Aborted";	
		}
	</script>
</head>
<body>
	<h2>HTML5 File Upload Progress Bar Tutorial</h2>
	<form id="upload_form" enctype="multipart/form-data" method="post">
		<input type="file" name="file1" id="file1"><br>
		<input id="uploadFileBtn" type="button" value="Upload File" onclick="uploadFile()" > <!--  -->
		<progress id="progressBar" value="0" max="100" style="width: 300px;"></progress>
		<h3 id="status"></h3>
		<p id="loaded_n_total"></p>
	</form>
</body>
</html>