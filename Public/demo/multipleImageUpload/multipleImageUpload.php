@{
    ViewBag.Title = "File Upload";
}
 
<div>
    <div>
        <p id="perror" style="color: white; background-color: red;"></p>
    </div>
    <div>
        <input type="file" accept="image/*" name="fileToUpload[]" id="fileToUpload" style='visibility: hidden;' name="img" onchange="fileSelected();"/>
        <input type = "button" value = "Choose image" onclick ="javascript:document.getElementById('fileToUpload').click();">
        <ul data-role="listview" id="uploadfilelist" data-inset="true" data-split-theme="d" data-split-icon="delete">
        </ul>
    </div>
    <div>
        <ul data-role="listview" data-inset="true">
            <li data-role="fieldcontain">
                <label id = "sizereducechkboxlbl" style="display: none">
                    <input type="checkbox" name="checkbox-0" id="sizereducechkbox" style="display: none"/> Reduce size and upload </label>
                <input type="button" onclick="uploadFiles();" value="Upload [max. 5 files]" />
            </li>
        </ul>
    </div>
</div>