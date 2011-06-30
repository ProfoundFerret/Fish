$(document).ready(function(){
	$('.adminImage img').load(function(){
		var height = $(this).height();
		$(this).parent().find('textarea').height(height);
	});
	
	$('.adminImage').hover(function(){
		$(this).find('.deleteImage').css('visibility', 'visible');
	}, function() {
		$(this).find('.deleteImage').css('visibility', 'hidden');
	});
	
	$('a.changePage').live('click', function(){
	    if ($(this).is('.disabled')) return false;
	
	    url = $(this).attr('href');
	    $('#changePage').load(url + " #changePage > *", "");
	
	    if (window.history.pushState) {
	        window.history.pushState(false, document.title, url);
	    }
	
	    return false;
	});
	
	$('table.gallery img').live('click', function(){
		url = $(this).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');
		title = $(this).attr('title');
		$('#bigImage').attr('src',url);
		$('#bigImageTitle').html(title);
		$('#bigImageLink').attr('href', url);
		return false;
	});
	
	preloaded = [];
	
	$('.gallery img').live('mouseover',function(){
		url = $(this).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');
	
	    if ($.inArray(url,preloaded) != -1) return;
	    preloaded.push(url);
	
		$("<img>").attr('src', url).css('display','none').appendTo("html");
	});
	
	$('#bigImage').click(function(){
	    $(this).toggleClass('big');
	
	    if ($(this).is('.big'))
	    {
	        $(this).animate({ maxWidth: 800, maxHeight: 600});
	    } else {
	        $(this).animate({ maxWidth: 600, maxHeight: 400});
	    }
	
	    return false;
	});
	
	$(".attractionImage").live('click',function(){
	    $(this).toggleClass('big');
	
		img = $(this).find('img');
	    url = $(img).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');
	    $(img).attr('src',url);
	
	    if ($(this).is('.big'))
	    {
	        $(this).animate({maxWidth: 500, maxHeight: 600});
	    } else {
	        $(this).animate({maxWidth: 300, maxHeight: 400});
	    }
	
	    return false;
	});
	
	$("#BaN a").each(function()
	{
		$(this).attr('href','');
	});
	
	$("#BaN").hover(function()
	{
		$(this).stop().animate({ backgroundColor: '#dddddd'});
	}, function ()
	{
		$(this).stop().animate({ backgroundColor: '#eeeeee'});
	});
		
	$("#BaN").click(function()
	{
		color = $(this).find('a').css('color');
		
		replace = function() { $(this).replaceWith('<b>' + $(this).html() + '</b>'); }
		
		$(this).find('a').animate({ color: 'black' }, "slow", "swing", replace);
		
		$("#BaN tr").fadeIn();
		$(this).unbind('mouseenter click');
		
		return false;
	});
	$("#BaN tr").hide();
	
	$("#BaN form").submit(function()
	{
		data = $(this).serialize();
	
		console.log(data);
		
		return false;
	});
});