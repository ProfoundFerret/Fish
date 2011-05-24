$(document).ready(function(){
	$('.gallery img').click(function(){
		url = $(this).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');
		title = $(this).attr('title');
		$('#bigImage').attr('src',url);
		$('#bigImageTitle').html(title);
		$('#bigImageLink').attr('href', url);
		return false;
	});
	$('.gallery img').mouseover(function(){
		url = $(this).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');

		$("<img>").attr('src', url).css('display','none').appendTo("html");
	});
	$('.adminImage img').load(function(){
		var height = $(this).height();
		$(this).parent().find('textarea').height(height);
	});

	$('.adminImage').hover(function(){
		$(this).find('.deleteImage').css('visibility', 'visible');
	}, function() {
		$(this).find('.deleteImage').css('visibility', 'hidden');
	});
});
