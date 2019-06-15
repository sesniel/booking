<template>
	<div class="form-group block">
			<label style="margin: 4px 0;"
				id="confirm-date"
				data-date-format="MM dd, yyyy"
				data-date-start-date="+1d" class="btn wb-btn-pink primary-text wb-datepicker">
				+ Add Date
			</label>
		<ul style="list-style: none; padding-left: 0;" claass="list-group">
			<li class="list-item" v-for="(date, index) in confirmedDates">
				{{ date }}
				<a href="#" @click.prevent="removeDate(index)"><i class="fa fa-remove text-danger"></i></a>
				<input type="hidden" name="confirmed_dates[]" :value="date">
			</li>
		</ul>
	</div>
</template>
<script>
	export default {
		computed: {
		},
		data() {
			return {
				confirmedDates: []
			}
		},
		methods: {
			addDate() {
				console.log('test');
			},
			removeDate(index) {
				this.confirmedDates.splice(index, 1);
			}
		},
		mounted() {
			let self = this;

			$('#confirm-date').datepicker()
			.on('changeDate', function(e){
				let d = e.format(null, 'MM dd, yyyy');
				if (!self.confirmedDates.includes(d)) {
					self.confirmedDates.push(d);
				}
			})

			if (window._wbQuote_ && window._wbQuote_.confirmedDates) {
				self.confirmedDates = window._wbQuote_.confirmedDates;
			}
		}
	}
</script>