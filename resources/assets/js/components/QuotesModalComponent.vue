<template>
	<table class="quote-receives">
		<thead>
			<tr class="quote-header">
				<th style="width: 70px;"></th>
				<th style="width: 17%;">Business</th>
				<th style="width: 15%;">Quote</th>
				<th style="width: 15%;">Star Rating</th>
				<th style="width: 15%;">Quote Expiry</th>
				<th style="width: 15%;">Status</th>
				<th style="width: 15%;"></th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(quote, index) in quotes.list">
				<td class="thumb">
					<img :src="quote.user.vendor_profile.profile_avatar || 'http://via.placeholder.com/61x61'" alt="" class="thumb">
				</td>
				<td>
					<div class="vendor">{{ quote.user.vendor_profile.business_name }}</div>
				</td>
				<td>
					<div class="amount">$ {{ quote.total }}</div>
				</td>
				<td>
					<div class="rating">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
					</div>
				</td>
				<td>
					<div class="date">{{ quote.duration }}</div>
				</td>
				<td>
                    <div class="date" v-if="quote.status == 1">Awaiting your response</div>
                    <div class="date" v-if="quote.status == 3">Changes requested</div>
                    <div class="date" v-if="quote.status == 4">Accepted & awaiting invoice</div>
                    <div class="date" v-if="quote.status == 5">Invoice received</div>
                    <div class="date" v-if="quote.status == 6">Paid & Confirmed</div>
                    <div class="date" v-if="quote.status == 7">Fully Paid</div>
                    <div class="date" v-if="quote.status == 8">Quote declined</div>
				</td>
				<td>
					<a :href="'/job-quotes/' + quote.id" class="btn wb-btn-outline-primary">View</a>
				</td>
			</tr>
			<tr v-show="quotes.list.length <= 0">
				<td colspan="6">
					<p class="text-center">Emtpy Job Quotes.</p>
				</td>
			</tr>
		</tbody>
	</table>
</template>
<script>
	export default {
		data() {
			return {
				quotes: window.wbQuotes,
			}
		}
	}
</script>
