$(document).ready(function(){
	$('.gallery img').click(function(){
		url = $(this).attr('src');
		url = url.replace(/\/thumbs\//, '/originals/');
		title = $(this).attr('title');
		$('#bigImage').attr('src',url);
		$('#bigImageTitle').html(title);
		return false;
	});
});
