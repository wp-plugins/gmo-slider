(function($) {
	$(window).on("load", function() {
	
		var $gmoslider = $(".gmoslider");

		if($gmoslider.length > 0) {
			
			$gmoslider.each(function() {
				var $this = $(this);
				var $videos = $this.find(".gmoslider_slide_video");
				
				var sessionId = $this.attr("data-session-id");
				
				var settings = window['GMOsliderSettings_' + sessionId];
				
				for(var key in settings) {
					if(settings[key] === "true") {
						settings[key] = true;
					} else if(settings[key] === "false") {
						settings[key] = false;
					} else if($.isNumeric(settings[key])) {
						settings[key] = parseInt(settings[key], 10);
					}
				}
				
				if($videos.length > 0) {
					var players = new Array();
				
					settings["before"] = function() {
						if(players.length > 0) {
							for(var i = 0; i < players.length; i++) {
								if(players[i].getPlayerState() === 1) {
									players[i].pauseVideo();
								}
							}
						}
					};
					
					settings["video"] = true;
					settings["pauseOnAction"] = true;
					$videos.each(function() {
						var $video = $(this);
						var $player = $video.find(".gmoslider_slide_video_id");
						$player.attr('id', 'gmoslider_slide_video_' + Math.floor(Math.random() * 1000000) + '_' + $player.text());
						
						var showRelatedVideos = $player.attr('data-show-related-videos');
						var player = null;
						var setYoutube = setInterval(function() {
							if($this.height() > 0) {
								clearInterval(setYoutube);
								player = new YT.Player(
									$player.attr('id'), {
										width: $this.width(),
										height: $this.height(),
										videoId: $player.text(),
										playerVars: {
											wmode: 'opaque',
											rel  : showRelatedVideos
										},
										events:
										{
											'onReady'      : function() {
												players.push(player);
											}
										}
									}
								);
								
								var $playerElement = $('#' + $player.attr('id'));
								$playerElement.show();
								$playerElement.attr('src', $playerElement.attr('src') + '&wmode=opaque');
							}
						}, 300);
					});
					
					
				}
				
				$this.flexslider(settings);
			});
		}
	});
})(jQuery);