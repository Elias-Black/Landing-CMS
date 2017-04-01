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
	var file_ifr_el	= document.getElementById(ifr_id);

	file_btn_el.file_ifr_el = file_ifr_el;
	file_inp_el.file_ifr_el = file_ifr_el;
	file_cls_el.file_ifr_el = file_ifr_el;

	function showElement(element)
	{
		if(!element.src)
			element.src = element.dataset.src;
		element.parentElement.classList.remove('hidden');
	}

	function hideElement(element)
	{
		element.parentElement.classList.add('hidden');
	}

	file_btn_el.onclick = function()
	{
		showElement(this.file_ifr_el);
	}

	file_btn_el.onkeypress = function(e)
	{
		if(e.keyCode == 13)
			showElement(this.file_ifr_el);
	}

	file_cls_el.onclick = function()
	{
		hideElement(this.file_ifr_el);
	}

	file_cls_el.onkeypress = function(e)
	{
		if(e.keyCode == 13)
			hideElement(this.file_ifr_el);
	}

	file_inp_el.onchange = function()
	{
		this.value = '/web/_cms/uploads/tinymce/source/' + this.value;
		hideElement(this.file_ifr_el);
	}

}