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
			return 'Do you want to leave this page with unsaved Fields?';

	}

	window.onbeforeunload = confirmMessage;

}



/* Called when the document is ready */

function init()
{

	/**
	* Adding listner for changes of all form's Fields except WYSIWYG Field
	* WYSIWYG Field itself calls -confirmLeave- function
	*/

	confirmLeave();

	var moveFieldControlls = document.querySelectorAll(".js_move_field_up, .js_move_field_down");
	attachOnClickEvent(moveFieldControlls, swapFields);

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

	file_inp_el.value = root_path + 'web/_cms/uploads/tinymce/source/' + file_inp_el.value;

}



/* AJAX actions */

function openCloseGroup(name)
{

	var toggle_id = 'js_group_toggle_'+name;

	var body_element = document.getElementById(name);
	var toggle_element = document.getElementById(toggle_id);

	var opened_img = toggle_element.getAttribute('data-opened');
	var closed_img = toggle_element.getAttribute('data-closed');

	if( hasClass(body_element, 'hidden') )
	{

		AJAX( root_path + 'cms/?ajax=true&openGroup='+name, function(data){}, function(data){} );
		toggle_element.setAttribute('src', opened_img);
		showElement(body_element);

	}
	else
	{

		AJAX( root_path + 'cms/?ajax=true&closeGroup='+name, function(data){}, function(data){} );
		toggle_element.setAttribute('src', closed_img);
		hideElement(body_element);

	}

}

function deleteField(name)
{

	var field = document.getElementById(name);
	var field_wrapper = getParentWithClass(field, 'form-group');

	addClass(field_wrapper, 'faded');

	AJAX( root_path + 'cms/?ajax=true&delete='+name, function(data) {

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
			removeClass(field_wrapper, 'faded');
		}

	}, function(data) {

		removeClass(field_wrapper, 'faded');

	} );

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

		addClass(field_wrapper, 'faded');

		function succes_callback(data)
		{

			data = isJson(data);

			if( data && data.success === true )
			{

				field_wrapper.parentElement.insertBefore(field_wrapper, new_element || null);

				removeClass(field_wrapper, 'faded');

			}
			else
			{
				error_callback();
			}

		}

		function error_callback(data)
		{
			window.location.reload();
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

}

function showElement(element)
{
	removeClass(element, 'hidden');
}

function hideElement(element)
{
	addClass(element, 'hidden');
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
