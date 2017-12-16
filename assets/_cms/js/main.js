/* Called when the document is ready */

function init()
{

	/**
	* Adding listner for changes of all form's Fields except WYSIWYG Field
	* WYSIWYG Field itself calls -confirmLeave- function
	*/

	confirmLeave();

	var move_field_controlls = document.querySelectorAll(".js_move_field_up, .js_move_field_down");
	attachOnClickEvent(move_field_controlls, swapFields);

	var groups_toggles = document.querySelectorAll(".js_group_toggle");
	attachOnClickEvent(groups_toggles, openCloseGroup);

	var delete_field_buttons = document.querySelectorAll(".js_delete_field");
	attachOnClickEvent(delete_field_buttons, deleteField);

	var sitemenu_toggles = document.querySelectorAll(".js_sitemenu_toggle");
	attachOnClickEvent(sitemenu_toggles, collapseMenu);

}



/* The function for mobile menu dropdown */

function collapseMenu()
{

	var _this = this;

	var element = document.getElementById('js_collapse');

	var open_title = _this.getAttribute('data-open-title');
	var close_title = _this.getAttribute('data-close-title');

	if(element.style.display == 'none' || element.style.display == '')
	{
		element.style.display = 'block';
		_this.setAttribute('title', close_title);
	}
	else
	{
		element.style.display ='none';
		_this.setAttribute('title', open_title);
	}

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
		{
			return leave_prevention_message;
		}

	}

	window.onbeforeunload = confirmMessage;

}



/* The function for initialize Color Picker */

function CPinit(cp_id, def_color)
{

	var picker_field = document.getElementById(cp_id);
	var picker_el = nextElementSibling(picker_field);

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
			var cp_input = previousElementSibling(this.target);

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

			if(previousElementSibling(CP.__instance__[i].target) == element)
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
		nextElementSibling(this).style.backgroundColor = this.value;

	}

	picker_el.picker 	= picker;

	picker_field.onchange = updateCP;
	picker_field.oncut = updateCP;
	picker_field.onpaste = updateCP;
	picker_field.onkeyup = updateCP;
	picker_field.oninput = updateCP;

	picker_el.onclick = function() {

		var cpicker = getPickerByElement( previousElementSibling(this) );
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

	file_inp_el.value = root_path + 'assets/_cms/uploads/tinymce/source/' + file_inp_el.value;

}



/* AJAX actions */

function openCloseGroup(name)
{

	var toggle_element = this;
	var field_wrapper = getParentWithClass(toggle_element, 'form-group');
	var groups_body = field_wrapper.querySelector('.panel-body');
	var toggle_img = toggle_element.getElementsByTagName("img")[0];

	var request_url = toggle_element.getAttribute('href');
	request_url = addGETParams(request_url, 'ajax=true');

	var opened_img = toggle_img.getAttribute('data-opened');
	var closed_img = toggle_img.getAttribute('data-closed');

	fade(field_wrapper);

	function error_callback(data)
	{

		var error_message_element = document.getElementById('js_error_message');

		data = isJson(data);

		if(data && data.error_message)
		{
			window.scrollTo(0, 0);
			unfade(field_wrapper);
			showElement(error_message_element).innerHTML = data.error_message;
		}
		else
		{
			window.location.reload();
		}

	}

	if( hasClass(groups_body, 'hidden') )
	{

		request_url = request_url.replace("closeGroup", "openGroup");

		var succes_callback = function(data)
		{

			data = isJson(data);

			if( data && data.success === true )
			{

				toggle_img.setAttribute('src', opened_img);
				showElement(groups_body);

				unfade(field_wrapper);

			}
			else
			{
				error_callback(data);
			}

		}

	}
	else
	{

		request_url = request_url.replace("openGroup", "closeGroup");

		var succes_callback = function(data)
		{

			data = isJson(data);

			if( data && data.success === true )
			{

				toggle_img.setAttribute('src', closed_img);
				hideElement(groups_body);

				unfade(field_wrapper);

			}
			else
			{
				error_callback(data);
			}

		}

	}

	AJAX(request_url, succes_callback, error_callback);

	return false;

}

function deleteField()
{

	var delete_button = this;

	var field_wrapper = getParentWithClass(delete_button, 'form-group');
	var confirm_message = delete_button.getAttribute('data-confirm-title');

	if( !confirm(confirm_message) ) return false;

	var request_url = delete_button.getAttribute('href');
	request_url = addGETParams(request_url, 'ajax=true');

	fade(field_wrapper);

	function succes_callback(data)
	{

		data = isJson(data);

		if( data && data.success === true )
		{
			field_wrapper.parentNode.removeChild(field_wrapper);

			var main_form_elements_counter = document.getElementById('js_main_form').querySelectorAll('.form-group').length;

			if(main_form_elements_counter < 1)
			{

				var empty_main_form = document.getElementById('js_empty_main_form');

				var filled_main_form = document.getElementById('js_filled_main_form');

				hideElement(filled_main_form);

				showElement(empty_main_form);

			}

		}
		else
		{
			error_callback(data)
		}

	}

	function error_callback(data)
	{

		var error_message_element = document.getElementById('js_error_message');

		data = isJson(data);

		if(data && data.error_message)
		{
			window.scrollTo(0, 0);
			unfade(field_wrapper);
			showElement(error_message_element).innerHTML = data.error_message;
		}
		else
		{
			window.location.reload();
		}

	}

	AJAX(request_url, succes_callback, error_callback);

	return false;

}

function swapFields(event)
{

	var _this = this;

	var field_wrapper = getParentWithClass(_this, 'form-group');

	if( hasClass(_this, 'js_move_field_up') )
	{
		var old_element = previousElementSibling(field_wrapper);
		var new_element = previousElementSibling(field_wrapper);
	}
	else
	{
		var old_element = nextElementSibling(field_wrapper);
		var new_element = nextElementSibling(old_element);
	}

	if( old_element && hasClass(old_element, 'form-group') )
	{

		var request_url = _this.getAttribute('href');
		request_url = addGETParams(request_url, 'ajax=true');

		fade(field_wrapper);

		function succes_callback(data)
		{

			data = isJson(data);

			if( data && data.success === true )
			{

				field_wrapper.parentElement.insertBefore(field_wrapper, new_element || null);

				unfade(field_wrapper);

			}
			else
			{
				error_callback(data);
			}

		}

		function error_callback(data)
		{

			var error_message_element = document.getElementById('js_error_message');

			data = isJson(data);

			if(data && data.error_message)
			{
				window.scrollTo(0, 0);
				unfade(field_wrapper);
				showElement(error_message_element).innerHTML = data.error_message;
			}
			else
			{
				window.location.reload();
			}

		}

		AJAX(request_url, succes_callback, error_callback);

	}

	return false;

}



/* Utils */

function hasClass(ele, cls)
{
	return ele.getAttribute('class').indexOf(cls) > -1;
}

function addClass(ele, cls)
{

	if(ele.classList)
	{
		ele.classList.add(cls);
	}
	else if(!hasClass(ele, cls))
	{
		ele.setAttribute('class', ele.getAttribute('class') + ' ' + cls);
	}

	return ele;

}

function removeClass(ele, cls)
{

	if(ele.classList)
	{
		ele.classList.remove(cls);
	}
	else if(hasClass(ele, cls))
	{
		ele.setAttribute('class', ele.getAttribute('class').replace(cls, ' '));
	}

	return ele;

}

function showElement(element)
{
	removeClass(element, 'hidden');
	return element;
}

function hideElement(element)
{
	addClass(element, 'hidden');
	return element;
}

function AJAX(url, succes_callback, error_callback)
{

	var cache_str = new Date().getTime();

	url = addGETParams(url, 'ie_cache='+cache_str);

	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function()
	{

		if(xhttp.readyState == 4 && xhttp.status == 200)
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

function getParentWithClass(element, cls)
{

	while ((element = element.parentNode) && element.className.indexOf(cls) < 0);

	return element;

}

function isJson(str)
{

	if(typeof str == 'object') return str;

	try
	{
		return JSON.parse(str);
	}
	catch(e)
	{
		return false;
	}

}

function addGETParams(url, params_str)
{

	var url_prefix = url.indexOf('?') > -1 ? '&' : '?';

	return url = url+url_prefix+params_str;

}

function attachOnClickEvent(elements, callback)
{

	for(var i = 0; i < elements.length; i++)
	{
		elements[i].onclick = callback;
	}

}

 function nextElementSibling(el)
 {

	if(!el) return false;

	if(el.nextElementSibling)
	{
		return el.nextElementSibling;
	}
	else
	{

		while(el = el.nextSibling)
		{
			if( el.nodeType === 1 ) return el;
		}

	}

	return false;

}

function previousElementSibling(el)
{

	if(!el) return false;

	if(el.previousElementSibling)
	{
		return el.previousElementSibling;
	}
	else
	{

		while(el = el.previousSibling)
		{
			if( el.nodeType === 1 ) return el;
		}

	}

	return false;

}

function fade(el)
{
	addClass(el, 'faded');
	return el;
}

function unfade(el)
{
	removeClass(el, 'faded');
	return el;
}
