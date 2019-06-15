<template>
	<table class="table" id="milestones-component">
		<thead>
			<tr style="font-weight: 400; color: #353554;">
				<th style="font-weight: 400; text-transform: capitalize;">Description</th>
				<th style="font-weight: 400; text-transform: capitalize;">% Due</th>
				<th style="font-weight: 400; text-transform: capitalize;">Amount</th>
				<th style="font-weight: 400; text-transform: capitalize;">Due Date</th>
				<th style="font-weight: 400; text-transform: capitalize;"></th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(milestone, index) in milestones">
				<td style="font-weight: 300; text-transform: capitalize;">
					{{ milestone.desc }}
					<input type="hidden"
					class="notes wb-border-round-xs wb-border-input"
					:value="milestone.desc"
					name="milestones[descs][]">
				</td>
				<td>
					<input style="width:85px;" type="number"
						v-on:keyup="validatePercent(index)"
						v-model="milestone.percent"
						name="milestones[percents][]">
				</td>
				<td class="cost">${{ compute(milestone.percent) }}</td>
				<td>
					<input type="text"
					:id="index"
					class="wb-datepicker due-date"
					v-model="milestone.due_date"
					v-on:blur="syncDate(index)"
					name="milestones[due_dates][]"
					data-date-format="MM dd, yyyy"
					data-date-start-date="+1d">
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
	export default {
		data() {
			return {
				milestones: [
					{percent: 0, amount: 0, due_date: '', desc: 'deposit'},
					{percent: 0, amount: 0, due_date: '', desc: 'balance'},
				],
				total: window._wbQuote_
			}
		},
		methods: {
			compute(percent) {
				if (percent && percent > 0 && isNaN(percent) === false) {
					return this.formatToDecimal(parseFloat((percent / 100) * this.total.totalPayable));
				}
				return 0;
			},
			syncDate(index) {
				this.milestones[index].due_date = $('#milestones-component #'+index).val();
			},
			formatToDecimal(num) {
				var val =  +(Math.round(num + "e+2")  + "e-2");
				return val > 0 ? val : '';
			},
			validatePercent(index) {
				let percentTotal = 0;

				for(let i in this.milestones) {
					percentTotal = parseInt(percentTotal) + parseInt(this.milestones[i].percent);
				}

				if (percentTotal > 100) {
					alert('Total percent must not exceeds 100%.');
					this.milestones[index].percent = null;
				}
			}
		},
		mounted() {
			$('.due-date').datepicker();
			if (window._wbQuote_ && window._wbQuote_.milestones) {
				this.milestones = window._wbQuote_.milestones;
			}
			this.compute();
		}
	}
</script>