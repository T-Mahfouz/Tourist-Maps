function changeActiveClass(thisItem){
	elemenstArray = document.getElementsByClassName("navigate-item");
	elemenstArray = [].slice.call(elemenstArray);
	for (var i = 0; i < elemenstArray.length; ++i)
		elemenstArray[i].classList.remove("active");
	
	thisItem.classList.add("active");	
}

function deleteAdmin(admin_id){
	if(confirm('هل تريد حذف هذا المدير؟')){
		$.ajax({
			method:'POST',
			url:admin_delete_admin_url,
			data:{admin_id:admin_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما أثناء الحذف');
		});
	}
}
function changeAdminStatus(admin){
	$('#status_admin_id').val(admin.id);
	$('#change_admin_status_modal').modal();
}
function editAdmin(admin,categories){

	$('#admin_id').val(admin.id);
	var cats = "";
	categories.forEach(function(item){
		cats += "<option value='"+item.id+"' ";
		admin.categories.forEach(function(item2){
			if(item.id == item2)
				cats += "selected ";
		});
		cats += ">"+ item.name_en+" - "+item.name_ar+"</option>";
	});
	$('#admin_categories').html(cats);
	$('#edit_admin_modal').modal();
}
function addAdmin(categoy){
	$('#add_admin_modal').modal();
}




function deleteUser(id){
	$('#delete_user_id').val(id);
	$('#delete_user_modal').modal();
}
function changeUserStatus(user){
	$('#status_user_id').val(user.id);
	$('#change_user_status_modal').modal();
}

function deleteCategory(categoy){
	$('#category-delete-id').val(categoy.id);
	$('#delete_categoy_modal').modal();
}
function editCategory(categoy){
	$('#category-edit-id').val(categoy.id);
	$('#category-edit-name_ar').val(categoy.name_ar);
	$('#category-edit-name_en').val(categoy.name_en);
	$('#category-edit-status').val(categoy.active);
	$('#edit_categoy_modal').modal();
}
function addCategory(){
	$('#add_categoy_modal').modal();
}

function deleteContinent(continent_id){
	if(confirm('هل تريد الحذف؟')){
		$.ajax({
			method:'POST',
			url:delete_url,
			data:{continent_id:continent_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert(delete_url);
		});
	}	
}

function deleteCountry(country_id){
	if(confirm('سوف يتم جميع الأماكن فى هذه البلد، هل تريد اتمام الحذف؟')){
		$.ajax({
			method:'POST',
			url:delete_coutry_url,
			data:{country_id:country_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			
		});
	}	
}


/*=========== Places ============*/
function deletePlace(place_id){
	if(confirm('هل تريد حذف هذا المكان')){
		$.ajax({
			method:'POST',
			url:delete_place_url,
			data:{place_id:place_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			
		});
	}	
}
function viewPlace(place){
	var place_images = place.images;
	images = ''
	if(place_images.length){
		place_images.forEach(function(img){
			images += '<img style="width: 10%;height: 75px;margin: 5px;" src="'+placeImagePath+img.image+'"/>'
		})
	}


	$('#view_place_name').html(place.name);
	$('#view_place_type').html(place.place_type.name);
	$('#view_booking_link').html(place.booking_link);
	$('#view_place_geolocation').html(place.lat+','+place.lon);
	$('#view_place_address').html(place.address);
	$('#view_place_continent').html(place.continent.name);
	$('#view_place_country').html(place.country.name);
	
	$('#view_place_images').html(images);
	$('#view_place_description').html(place.description);
	$('#view_place_modal').modal();
}
function editPlace(post){
	var content = post.content.replace(new RegExp('<br />', 'g'),' ');
	let checked = false;
	if(post.special == 1)
		checked = true;
	
	$('#post-edit-id').val(post.id);
	$('#post-edit-category').val(post.category_id);
	$('#post-edit-title').val(post.title);
	$('#post-edit-content').val(content);
	$('#post-edit-keywords').val(post.keywords);
	$('#post-edit-video').val(post.video);
	$('#post-edit-special').prop('checked', checked);
	$('#edit_post_modal').modal();
}
function changePlaceStatus(place_id){
	if(confirm('هل تريد تغيير حالة المكان؟')){
		$.ajax({
			method:'POST',
			url:change_place_status_url,
			data:{place_id:place_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			
		});
	}
}

/*=========== Places-Types ============*/
function deletePlaceType(placeType_id){
	if(confirm('هل تريد حذف هذا المكان')){
		$.ajax({
			method:'POST',
			url:delete_placetype_url,
			data:{placeType_id:placeType_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteSlider(slider_id){
	if(confirm('هل تريد حذف هذه الصورة؟')){
		$.ajax({
			method:'POST',
			url:delete_slider_url,
			data:{slider_id:slider_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}


function editAboutUs(aboutus){
	$('#aboutus-edit-title').val(aboutus.title);
	$('#aboutus-edit-content').val(aboutus.content);

	$('#edit_aboutus_modal').modal();
}
function editIntro(intro){
	$('#intro-edit-title').val(intro.title);
	$('#intro-edit-content').val(intro.content);

	$('#edit_intro_modal').modal();
}

function deleteContactLink(contactLink_id){
	if(confirm('هل تريد حذف رابط التواصل هذا؟')){
		$.ajax({
			method:'POST',
			url:delete_contactlink_url,
			data:{contactLink_id:contactLink_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteExtrnalLink(externalLink_id){
	if(confirm('هل تريد حذف هذا الرابط')){
		$.ajax({
			method:'POST',
			url:delete_externalLink_url,
			data:{externalLink_id:externalLink_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteAmbassador(ambassador_id){
	if(confirm('هل تريد حذف هذا الرابط')){
		$.ajax({
			method:'POST',
			url:delete_ambassador_url,
			data:{ambassador_id:ambassador_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteOffer(offer_id){
	if(confirm('هل تريد حذف هذا العرض؟')){
		$.ajax({
			method:'POST',
			url:delete_offer_url,
			data:{offer_id:offer_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteMessage(messages_id){
	if(confirm('هل تريد حذف هذه الرسالة؟')){
		$.ajax({
			method:'POST',
			url:delete_message_url,
			data:{messages_id:messages_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}

function deleteGuidebook(guidebook_id){
	if(confirm('هل تريد حذف هذا الكتاب؟')){
		$.ajax({
			method:'POST',
			url:delete_guidebook_url,
			data:{guidebook_id:guidebook_id,_token:token}
		}).then(function(response){
			alert(response);
			location.reload();
		}).catch(function(error){
			alert('حدث خطأ ما يرجى المحاولة لاحقا');
		});
	}	
}


$("#filter-posts").change(function(){
	var sendData = {_token:token,filter: $("#filter-posts").val()};
	if(title)
		sendData['title'] = title;
	if(category)
		sendData['category'] = category;
	if(adminParam)
		sendData['admin'] = adminParam;
	if(currentPage)
		sendData['page'] = currentPage;
	

	$.ajax({
		method:'POST',
		url:adminPostsPath,
		data:sendData
	}).done(function(response){
		$('#body').html(response);
	});

	var url = new URL(document.URL);
	var query_string = url.search;
	var search_params = new URLSearchParams(query_string);
	search_params.set('filter', $("#filter-posts").val());
	if(title)
		search_params.set('title', title);
	else
		search_params.delete('title');
	if(category)
		search_params.set('category', category);
	else
		search_params.delete('category');

	if(adminParam)
		search_params.set('admin', adminParam);
	else
		search_params.delete('admin');

	if(currentPage)
		search_params.set('page', currentPage);
	else
		search_params.delete('page');
	
	url.search = search_params.toString();
	var new_url = url.toString();

	window.history.pushState("", "", new_url);

	/*var oldURL = document.URL;
	var index = 0;
	var page = 0;
	index = oldURL.indexOf('page');
	if(index != -1){
	    page = index;
	}*/

	

	/*var oldURL = document.URL;
	var index = 0;
	var page = 0;
	var newURL = oldURL;
	index = oldURL.indexOf('?');
	if(index == -1){
	    index = oldURL.indexOf('#');
	}
	if(index != -1){
	    newURL = oldURL.substring(0, index);
	}
	window.history.pushState("", "", newURL);*/
});