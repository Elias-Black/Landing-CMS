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

	function updateCP() {

		if( CP.parse(this.value).toString() != this.picker.set().toString() )
			this.form.onchange();

		this.picker.set( CP.parse(this.value) );
		this.nextElementSibling.style.backgroundColor = this.value;

	}

	picker_field.picker = picker;
	picker_el.picker 	= picker;

	picker_field.onchange = updateCP;
	picker_field.oncut	= updateCP;
	picker_field.onpaste  = updateCP;
	picker_field.onkeyup  = updateCP;
	picker_field.oninput  = updateCP;

	picker_el.onclick = function(){
		onCP(this.picker);
	};

}