import $ from 'jQuery';



(function(obj) {
	const { initialize, render } = obj
	const f = _.extend(obj, {
		events: _.extend(obj.events,{
			'click .sweet-spot-wrap': 'setSweetSpot'
		}),
		// initialize: function() {
		// 	const ret = initialize.apply(this, arguments)
		// 	return ret
		// },
		setSweetSpot: function(e) {
			const { offsetX, offsetY } = e.originalEvent
			this.inputX.value = offsetX / e.target.width
			this.inputY.value = offsetY / e.target.height
			this.updateCb()
			this.inputY.dispatchEvent(new CustomEvent('change',{bubbles:true}))
			// this.inputY.dispatchEvent(new ChangeEvent('change'))
		},
		render: function() {
			const ret = render.apply(this, arguments)
			const $img = this.$el.find('.thumbnail-image > img').first()
			const img = $img.get(0)
			this.updateCb = e => {
				this.$el.css({
					'--sweet-spot-x': this.inputX.value,
					'--sweet-spot-y': this.inputY.value,
				})
			}
			img.onload = e => {
				this.$el.css({
					'--image-ratio': img.naturalWidth / img.naturalHeight
				})
				img.setAttribute('width',img.naturalWidth)
				img.setAttribute('height',img.naturalHeight)
			}
			$img.wrap('<div class="sweet-spot-wrap"></div>')

			this.inputX = this.el.querySelector('[name="sweet_spot_x"]')
			this.inputY = this.el.querySelector('[name="sweet_spot_y"]')
			this.inputX.addEventListener('input', this.updateCb )
			this.inputY.addEventListener('input', this.updateCb )
			this.updateCb()
			return ret
		}
	})
	console.log(f.events)
})(wp.media.view.Attachment.Details.TwoColumn.prototype);

//
// (function(obj) {
// 	const { initialize, render } = obj
// 	_.extend(obj, {
// 		// initialize: function() {
// 		// 	const ret = initialize.apply(this, arguments)
// 		// 	return ret
// 		// },
// 		render: function() {
// 			const ret = render.apply(this, arguments)
// 			const $img = this.$el.find('.thumbnail-image > img').first()
// 			const img = $img.get(0)
//
// 			// img.setAttribute('width',img.naturalWidth)
// 			// img.setAttribute('height',img.naturalHeight)
// 			$img
// 				.wrap('<div class="sweet-spot-wrap"></div>')
// 			return ret
// 		}
// 	})
// })(wp.media.view.MediaFrame.prototype);


// (function(obj) {
// 	const { initialize, render } = obj.prototype
// 	_.extend(wp.media.view.MediaFrame.prototype, {
// 		initialize: function() {
// 			const ret = initialize.apply(this, arguments)
// 			console.log(this)
// 			return ret
// 		},
// 		render: function() {
// 			const ret = render.apply(this, arguments)
// 			console.log(this.$el)
// 			return ret
// 		}
// 	})
// })(wp.media.view.MediaFrame)
