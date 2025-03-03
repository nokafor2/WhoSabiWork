/***********************************

  fileSelected – this function is used to show preview of the selected file. 
  Also I have written some validations such as only image types are allowed, 
  maximum file size is 10MB, no.of files that can be uploaded at once cannot 
  be higher than 5. Remember that your browser should be supported HTML5 File Api.
  
  uploadFiles – loop through filequeue and popping up the file and the list-element.
  
  resizeAndUpload – this function does the size reducing before uploading. 
  At the moment I have used the MAX Height as 600 and MAX Width as 800. 
  I have use the canvas element and redraw the original image with the max 
  height, width dimensions.  XMLHttpRequest object is used to send the 
  data(canvas element can be converted to base64string with the use of 
  toDataURL(“image/jpeg”)) to server.

************************************/

<script type="text/javascript">
 
    var count = 0;
    var progresscount = 0;
    var start;
    var chromeCount = 0;
    var i = 0;
 
    var filequeue = new Array();
    var jobimageuploadid = -1;
 
    //displaying selected file details
    function fileSelected() {
 
        try {
 
            count = 0;
            progresscount = 0;
            var selectedfile;
            var processingfile;
            var processingfileuniquekey;
 
            $("#perror").text('').fadeIn(1);
            $("#uploadfilelist").listview("refresh");
 
            var files = document.getElementById('fileToUpload').files;
 
            if (filequeue.length > 4) {
                errorDisplay('Maximum number of allowable file uploads has been exceeded!!');
            } else {
 
                selectedfile = files[0];
 
                if (!selectedfile.type.match('image.*')) {
                    errorDisplay('Only image files are allowed');
                } else if (selectedfile.size > 10485760) {
                    errorDisplay('Maximum file size exceeds');
                }
 
                if (selectedfile.type.match('image.*') && selectedfile.size < 10485760) {
 
                    $("#sizereducechkbox").show("slow");
                    $("#sizereducechkboxlbl").show("slow");
 
                    processingfileuniquekey = "PC:00" + chromeCount + "-";
                    var map = new Array(selectedfile, processingfileuniquekey);
 
                    if (window.File && window.FileReader && window.FileList && window.Blob) {
 
                        var reader = new FileReader();
 
                        reader.onload = (function(theFile) {
                            return function(e) {
 
                                var li = document.createElement("li");
                                li.setAttribute("data-icon", "delete");
 
                                var newlink = document.createElement('a');
                                newlink.setAttribute("href", "javascript:void(0);");
                                newlink.setAttribute("onclick", "removeImage('" + processingfileuniquekey + "');");
 
                                var img = document.createElement("img");
                                img.setAttribute("src", e.target.result);
                                img.setAttribute("height", "80");
                                img.setAttribute("width", "80");
                                newlink.appendChild(img);
 
                                var h3 = document.createElement("h3");
                                var h3Text = document.createTextNode(processingfileuniquekey + theFile.name);
                                h3.appendChild(h3Text);
                                newlink.appendChild(h3);
 
                                var p = document.createElement("p");
                                newlink.appendChild(p);
 
                                li.appendChild(newlink);
 
                                document.getElementById("uploadfilelist").appendChild(li);
 
                                $("#uploadfilelist").listview("refresh");
 
                                filequeue.push({ file: map, li: li });
 
                                chromeCount = chromeCount + 1;
 
                            };
                        })(selectedfile);
 
                        // Read in the image file as a data URL.
                        reader.readAsDataURL(selectedfile);
 
                    } else {
 
                        $("#uploadfilelist").append('<li data-icon="delete" id=' + i + '><a href="javascript:void(0);" onclick="remove(' + i + ');">' + processingfile.name + '<p style="padding: 2px 0px 0px 0px"></p></a></li>');
                        $("#uploadfilelist").listview("refresh");
 
                        i = i + 1;
                    }
 
                }
            }
 
            $("#fileToUpload").val("");
 
        } catch(err) {
            alert("Exception " + err);
        }
    }
 
    //removing selected file if needed, before uploading
    function removeImage(processingfileuniquekey) {
 
        try {
 
            $("#uploadfilelist li").each(function() {
 
                if ($(this).find("h3").text().indexOf(processingfileuniquekey) != -1) {
 
                    var containtext = $(this).find("h3").text();
 
                    $(this).remove();
 
                    for (var b = 0; b < filequeue.length; b++) {
 
                        var processarray = filequeue[b];
                        var processmap = processarray.file;
 
                        if (containtext.indexOf(processmap[1]) != -1) {
 
                            filequeue.splice(b, 1);
 
                            break;
                        }
                    }
 
                    $("#uploadfilelist").listview("refresh");
 
                }
 
            });
 
            if (filequeue.length == 0) {
                $('#sizereducechkbox').attr('checked', false);
                $("#sizereducechkbox").checkboxradio("refresh");
                $("#sizereducechkbox").fadeOut(100);
                $("#sizereducechkboxlbl").fadeOut(100);
            }
 
            $("#uploadfilelist").listview("refresh");
 
        } catch(err) {
            alert("Exception " + err);
        }
    }
 
    //uploading files
    function uploadFiles() {
 
        try {
 
            while (filequeue.length > 0) {
 
                var item = filequeue.pop();
                var processarray = item.file;
                var file = processarray[0];
                var key = processarray[1];
 
                if ($('#sizereducechkbox').is(':checked')) {
                    resizeAndUpload(file, key);
                }
                else {
                    upload(file, key);
                }
            }
 
        } catch(err) {
            alert("Exception " + err);
        }
 
    }
 
    //used to prevent caching of the ajax request(specially with Mobile Safari)
    function GUID() {
        var S4 = function () {
            return Math.floor(
                Math.random() * 0x10000 /* 65536 */
            ).toString(16);
        };
 
        return (
            S4() + S4() + "-" +
                S4() + "-" +
                    S4() + "-" +
                        S4() + "-" +
                            S4() + S4() + S4()
        );
    }
 
    //default upload file function
    function upload(file, key, item) {
 
        try {
 
            var uploadurl = '@Url.Action("UploadFiles", "FileUpload")';
            uploadurl += "?bustCache=" + GUID();
 
            var li = item.li;
 
            var xhr = new XMLHttpRequest(), upload = xhr.upload;
 
            upload.addEventListener("loadstart", function (ev) {
 
                var containtext = $(li).find("h3").text();
                var index = containtext.indexOf(" upload error");
 
                if (index != -1) {
 
                    var refreshtext = containtext.substring(0, index);
 
                    $(li).find('h3').text(refreshtext);
                    $("#uploadfilelist").listview("refresh");
 
                }
                else {
 
                    $("#uploadfilelist").listview("refresh");
 
                }
 
            }, false);
 
            upload.addEventListener("progress", function (ev) {
 
                if (ev.lengthComputable) {
                    var percentComplete = Math.round(ev.loaded * 100 / ev.total);
                    $(li).find("p").text("Uploading " + percentComplete + "%");
                    $(li).find("p").css("color", "#3DD13F");
                }
 
            }, false);
            upload.addEventListener("load", function (evt) {
 
                $(li).find("h3").css("color", "#3DD13F");
 
            }, false);
            upload.addEventListener("error", function (ev) {
 
                if (xhr.status != 500) {
 
                    filequeue.push(item);
 
                    var containtext = $(li).find("h3").text();
                    $(li).find("h3").text(containtext + " upload error");
                    $(li).find("h3").css("color", "#FF0000");
                    $(li).find("p").text("Uploading 0%");
                    $(li).find("p").css("color", "#FF0000");
 
                }
 
            }, false);
 
            xhr.open("POST", uploadurl);
 
            xhr.setRequestHeader("Content-type", "multipart/form-data");
 
            xhr.setRequestHeader("X-File-Name", (key + file.name));
 
            xhr.setRequestHeader("X-File-Size", file.size);
            xhr.setRequestHeader("X-File-Type", file.type);
 
            xhr.send(file);
 
            xhr.onreadystatechange = function () {
 
                var containtext;
 
                if (xhr.readyState != 4) {
                    return;
                }
 
                else if (xhr.readyState == 4) {
 
                    if (xhr.status == 500) {
 
                        filequeue.push(item);
 
                        containtext = $(li).find("h3").text();
                        $(li).find("h3").text(containtext + " upload error");
                        $(li).find("h3").css("color", "#FF0000");
                        $(li).find("p").text("Uploading 0%");
                        $(li).find("p").css("color", "#FF0000");
 
                    }
                    else if (xhr.status == 200) {
 
                        containtext = $(li).find("h3").text();
                        $(li).find("h3").text(containtext + " upload complete");
                        $(li).fadeOut(5000);
                    }
 
                }
 
            };
 
            if (filequeue.length == 0) {
 
                $('#sizereducechkbox').attr('checked', false);
                $("#sizereducechkbox").checkboxradio("refresh");
                $("#sizereducechkbox").fadeOut(100);
                $("#sizereducechkboxlbl").fadeOut(100);
 
                chromeCount = 0;
 
            }
 
        }
 
        catch (err) {
            alert("Exception " + err);
        }
    }
 
    //size reduced file upload
    function resizeAndUpload(file, key, item) {
 
        try {
 
            var uploadurl = '@Url.Action("UploadSizeReducedFiles", "FileUpload")';
            uploadurl += "?bustCache=" + GUID();
 
            //due to problem occurs while resizing of the image with ios-devices/safari browser
            var mpImg = new MegaPixImage(file);
 
            var li = item.li;
 
            var reader = new FileReader();
            reader.onloadend = function (evt) {
 
                if (evt.target.readyState == FileReader.DONE) {
 
                    var tempImg = new Image();
                    tempImg.src = reader.result;
                    tempImg.onload = function () {
 
                        var MAX_WIDTH = 800;
                        var MAX_HEIGHT = 600;
 
                        var tempW = tempImg.width;
                        var tempH = tempImg.height;
 
                        if (tempW > MAX_WIDTH) {
                            tempW = MAX_WIDTH;
                        }
 
                        if (tempH > MAX_HEIGHT) {
                            tempH = MAX_HEIGHT;
                        }
 
                        var canvas = document.createElement('canvas');
                        //render canvas with the use of MegaPixImage library
                        mpImg.render(canvas, { maxWidth: tempW, maxHeight: tempH });
 
                        var xhr = new XMLHttpRequest(), upload = xhr.upload;
 
                        upload.addEventListener("loadstart", function (ev) {
 
                            var containtext = $(li).find("h3").text();
                            var index = containtext.indexOf(" upload error");
 
                            if (index != -1) {
 
                                var refreshtext = containtext.substring(0, index);
 
                                $(li).find('h3').text(refreshtext);
                                $("#uploadfilelist").listview("refresh");
 
                            }
                            else {
 
                                $("#uploadfilelist").listview("refresh");
 
                            }
 
                        }, false);
 
                        upload.addEventListener("progress", function (ev) {
 
                            if (ev.lengthComputable) {
                                var percentComplete = Math.round(ev.loaded * 100 / ev.total);
                                $(li).find("p").text("Uploading " + percentComplete + "%");
                                $(li).find("p").css("color", "#3DD13F");
                            }
 
                        }, false);
                        upload.addEventListener("load", function (ev) {
 
                            $(li).find("h3").css("color", "#3DD13F");
 
                        }, false);
                        upload.addEventListener("error", function (ev) {
 
                            if (xhr.status != 500) {
 
                                filequeue.push(item);
 
                                var containtext = $(li).find("h3").text();
                                $(li).find("h3").text(containtext + " upload error");
                                $(li).find("h3").css("color", "#FF0000");
                                $(li).find("p").text("Uploading 0%");
                                $(li).find("p").css("color", "#FF0000");
 
                            }
 
                        }, false);
 
                        xhr.open("POST", uploadurl);
                        xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
                        xhr.setRequestHeader("X-File-Name", (key + file.name));
 
                        xhr.setRequestHeader("X-File-Size", file.size);
                        xhr.setRequestHeader("X-File-Type", file.type);
 
                        var data = 'image=' + canvas.toDataURL("image/jpeg");
                        xhr.send(data);
 
                        xhr.onreadystatechange = function () {
 
                            var containtext;
 
                            if (xhr.readyState != 4) {
                                return;
                            }
 
                            else if (xhr.readyState == 4) {
 
                                if (xhr.status == 500) {
 
                                    filequeue.push(item);
 
                                    containtext = $(li).find("h3").text();
                                    $(li).find("h3").text(containtext + " upload error");
                                    $(li).find("h3").css("color", "#FF0000");
                                    $(li).find("p").text("Uploading 0%");
                                    $(li).find("p").css("color", "#FF0000");
 
                                }
                                else if (xhr.status == 200) {
 
                                    containtext = $(li).find("h3").text();
                                    $(li).find("h3").text(containtext + " upload complete");
                                    $(li).fadeOut(5000);
                                }
 
                            }
 
                        };
 
                        if (filequeue.length == 0) {
 
                            $('#sizereducechkbox').attr('checked', false);
                            $("#sizereducechkbox").checkboxradio("refresh");
                            $("#sizereducechkbox").fadeOut(100);
                            $("#sizereducechkboxlbl").fadeOut(100);
 
                            chromeCount = 0;
 
                        }
 
                    };
 
                };
 
            };
            reader.readAsDataURL(file);
        }
 
        catch (err) {
            alert("Exception " + err);
        }
    }
 
    //display error information
    function errorDisplay(error) {
        $("#perror").text(error).fadeOut(4000);
    }
 
 </script>