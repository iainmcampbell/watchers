$(function(){

	function watchers(){

		var aboutShowing = false
		var blocks = {}
		var f = null

		/* tower block */
		var block = function(){
			
			this.obj = null
			var active = false
			var frames = [], currentFrame, max, gif

			this.init = function(_obj){
				this.obj = _obj

				var count = 1;
				this.obj.find('img.png').each(function(){
					frames.push($(this))
				})

				currentFrame = 1;
				max = frames.length
				frames[0].addClass('show')

				gif = this.obj.find('img.gif')
			}

			this.toggle = function(){

				// deactivate all
				$.each(blocks,function(i,v){
					v.obj.removeClass('active')
				})

				// if inactive, re-activate
				if(!active) {
					this.obj.addClass('active')
					console.log('activating '+this.obj.className)
				}
					

				active = !active;

			}

		} // end block()



		var init = function() {

			// BLOCK SETUP
			$('#watchers').find('.block').each(function(i,v){
				var id = $(v).attr('id')

				if(id) { // not empty block

					blocks[id] = new block()
					blocks[id].init($(v))

					$(v).on('click',function(){
						blocks[id].toggle()
					})

				}
				
			})

			// clicking on the background closes the active block
			$('body').on('click', function(e){
				if(e.target == this)
					$.each(blocks,function(i,v){
					v.obj.removeClass('active')
				})
			})

			// ABOUT 
			$('#info').on('click',function(){

				var aboutheight = $('#about').height() - 15

				if(aboutShowing) {
					$('#about').css('bottom','-'+aboutheight+'px')
					$('#watchers').css('bottom','13px')
				} else {
					$('#about').css('bottom','0px')
					$('#watchers').css('bottom', 13+aboutheight+'px')
				}

				aboutShowing = !aboutShowing
			})



		} // end init()



		$(window).load(function() {
			console.log('HI')

			// Intro Animation

			var aboutheight = $('#about').height() - 15	
			$('#about').css('bottom','-'+aboutheight+'px')

			setTimeout(function() {

				$('#watchers').addClass('bottom')
				$('#watchers').css('bottom','13px')

				$('#cloud').addClass('top')
				$('#cloud').css('top','0px')

			}, 1000)

			setTimeout(function() {
			  $('body').removeClass('noOverflow')
			}, 3000)

			// TODO: permalinking system

			init();
		})
	}


	$(document).ready(function(){

		// set up elements off screen
		$('#watchers').css('bottom', '-' + ( $('#watchers').height() + 20 ) + 'px')
		$('#cloud').css('top', '-150px')

		watchers()
	})


})