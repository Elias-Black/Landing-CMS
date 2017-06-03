/* The function for mobile menu dropdown */

function collapseMenu()
{

	var element = document.getElementById('js_collapse');

	element.style.display = (element.style.display == 'none' || element.style.display == '') ? 'block' : 'none';

}



/* The function for notification about unsaved changed forms */

function confirmLeave(change)
{

	var form_state = false;

	if(change == 'on')
		form_state = true;

	function changeHandler()
	{
		form_state = true;
	}

	function submitHandler()
	{
		form_state = false;
	}

	var forms = document.getElementsByTagName('form');

	for (i=0; i<forms.length; i++)
	{

		forms[i].onchange = changeHandler;

		forms[i].onsubmit = submitHandler;

	}

	function confirmMessage()
	{

		if(form_state == true)
			return 'Do you want to leave this page with unsaved fields?';

	}

	window.onbeforeunload = confirmMessage;

}



/**
* Adding listner for changes of all form's fields except WYSIWYG field
* WYSIWYG field itself calls -confirmLeave- function
*/

document.addEventListener( 'DOMContentLoaded', confirmLeave, false );



/* The function for initialize Color Picker */

function CPinit(cp_id, def_color)
{

	var picker_field = document.getElementById(cp_id);
	var picker_el = picker_field.nextElementSibling;

	var picker = new CP( picker_el, 'focus' );

	if( def_color )
	{
		picker.set( CP.parse(def_color) );
		onCP(picker);
	}

	function onCP(picker)
	{

		picker.on('change', function(color) {

			var color_code = '#' + color;
			var cp_input = this.target.previousElementSibling;

			if(cp_input.value != color_code)
				cp_input.form.onchange();

			cp_input.value = color_code;
			this.target.style.backgroundColor = color_code;

		});

	}

	function getPickerByElement(element)
	{

		var i=0;

		while(CP.__instance__[i])
		{

			if(CP.__instance__[i].target.previousElementSibling == element)
				return CP.__instance__[i];

			i++;

		}

	}

	function updateCP()
	{

		var cpicker = getPickerByElement(this);

		if( CP.parse(this.value).toString() != cpicker.set().toString() )
			this.form.onchange();

		cpicker.set( CP.parse(this.value) );
		this.nextElementSibling.style.backgroundColor = this.value;

	}

	picker_el.picker 	= picker;

	picker_field.onchange = updateCP;
	picker_field.oncut	= updateCP;
	picker_field.onpaste  = updateCP;
	picker_field.onkeyup  = updateCP;
	picker_field.oninput  = updateCP;

	picker_el.onclick = function() {

		var cpicker = getPickerByElement(this.previousElementSibling);
		onCP(cpicker);

	};

}



/* The function for initialize File Uploader */

function fileUpInit(inp_id, btn_id, cls_id, ifr_id)
{

	var file_inp_el = document.getElementById(inp_id);
	var file_btn_el = document.getElementById(btn_id);
	var file_cls_el = document.getElementById(cls_id);


	// Utils

	function showFileUploader(element)
	{
		if(!element.src)
			element.src = element.dataset.src;
		showElement(element.parentElement)
	}

	function hideFileUploader(element)
	{
		hideElement(element.parentElement)
	}


	// Open the iframe

	file_btn_el.onclick = function()
	{
		var element = document.getElementById(this.dataset.iframeId);
		showFileUploader(element);
	}

	file_btn_el.onkeypress = function(e)
	{
		if(e.keyCode == 13)
		{
			var element = document.getElementById(this.dataset.iframeId);
			showFileUploader(element);
		}
	}

	file_cls_el.onclick = function()
	{
		var element = document.getElementById(this.dataset.iframeId);
		hideFileUploader(element);
	}


	// Close the iframe

	file_cls_el.onkeypress = function(e)
	{
		if(e.keyCode == 13)
		{
			var element = document.getElementById(this.dataset.iframeId);
			hideFileUploader(element);
		}
	}

	file_inp_el.onchange = function()
	{
		var element = document.getElementById(this.dataset.iframeId);
		hideFileUploader(element);
	}

}



/* The callback function for File Uploader */

function responsive_filemanager_callback(inp_id)
{

	var file_inp_el = document.getElementById(inp_id);

	file_inp_el.value = '/web/_cms/uploads/tinymce/source/' + file_inp_el.value;

}



/* Opening/Closing Group's body */

function openCloseGroup(name)
{

	event.preventDefault();

	var id = 'js_group_body_'+name;

	var element = document.getElementById(id);

	if( hasClass(element, 'hidden') )
	{

		AJAX( '/cms/?openGroup='+name, function(data){}, function(data){} );
		showElement(element);

	}
	else
	{

		AJAX( '/cms/?closeGroup='+name, function(data){}, function(data){} );
		hideElement(element);

	}

}



/* Utils */

function hasClass(element, cls) {
	return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

function showElement(element)
{
	element.classList.remove('hidden');
}

function hideElement(element)
{
	element.classList.add('hidden');
}

function AJAX(url, succes_callback, error_callback)
{

	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function()
    {

		if (xhttp.readyState == 4 && xhttp.status == 200)
        {
			succes_callback(xhttp.responseText);
		}

		if(xhttp.readyState == 4 && xhttp.status != 200)
		{
			error_callback(xhttp.responseText);
		}

	}

	xhttp.open("GET", url, true);

	xhttp.send();

}