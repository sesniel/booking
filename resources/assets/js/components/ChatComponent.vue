<template>
	<div class="wb-chat-messages">
		<div class="new-message" style="text-align: right; margin-bottom: 10px;">
			<a href="#" data-toggle="modal" data-target="#wb-modal-message" class="btn wb-btn-orange">+ NEW MESSAGE</a>
		</div><!-- /.new-message -->
		<div class="wb-chat-log-wrapper">
			<div class="wb-chat-msg"
				:class="{ 'right': message.user_id == myUserId, 'left': !message.user_id === myUserId }"
				v-for="(message, index) in chatMessages">
				<div class="wb-chat-img-wrapper">
					<img class="wb-chat-img"
						:src="message.user_id == myUserId ? myAvatar || 'http://via.placeholder.com/128x128' : recipientAvatar || 'http://via.placeholder.com/128x128'"
						alt="message user image">
					<div class="wb-chat-timestamp">{{ formatDate(message.created_at) }}</div>
				</div>
				<div class="wb-chat-text" v-if="message.type == 'image'">
					<img class="wb-chat-attach-img" :src="message.body" alt="message user image">
				</div>
				<div class="wb-chat-text" v-else>
					{{ message.body }}
				</div>
			</div>
		</div>
		<div class="wb-chat-title" v-show="someoneIsTyping && conversationId">
			<span class="name">{{ userThatIsTyping }}</span>
			<span class="details">is typing...</span>
		</div>
		<div class="wb-chat-composer" v-if="conversationId">
			<input @change="onFileChange" type="file" name="user_file" id="user-file" class="hidden">
			<label for="user-file" type="button" class="btn btn-clear attach"><i class="fa fa-paperclip"></i></label>
			<input type="text" placeholder="Type your message" @keydown="isTyping()" v-model="message" @keyup.enter="send()">
			<button @click.prevent="send()" type="button" class="btn btn-primary btn-rnd send"><i class="fa fa-paper-plane"></i></button>
		</div>
	</div>
</template>

<script>
	import Moment from 'moment';

	export default {
		props: ['myAvatar', 'myUserId', 'conversationId', 'title', 'messages', 'recipientAvatar'],
		data() {
			return {
				message: '',
				chatMessages: [],
				someoneIsTyping: false,
				userThatIsTyping: ''
			}
		},
		methods: {
			onFileChange(event) {
				let self = this;
				let form = new FormData();
				let file = event.target.files[0];

				if (!file) {
					return false;
				}

				NProgress.start();

				form.append('messageType', 'image');
				form.append('message', event.target.files[0]);
				form.append('conversationId', self.conversationId);

				axios.post('/dashboard/messages', form)
				.then(function (response) {
					self.chatMessages.unshift({
						user_id: response.data.user_id,
						type: 'image',
						body: response.data.body,
						time: self.formatDate(response.data.created_at)
					});
					self.done();
				});
			},
			send() {
				let self = this;

				if (!this.message) {
					return false;
				}

				NProgress.start();

				axios.post('/dashboard/messages', {
					message: self.message,
					conversationId: self.conversationId
					})
					.then(function (response) {
						self.chatMessages.push({
							user_id: response.data.user_id,
							body: response.data.body,
							time: self.formatDate(response.data.created_at)
						});
						self.done();
					});
			},
			isTyping() {
				const self = this;
				setTimeout(function() {
					Echo.private(`conversation.${self.conversationId}`)
					.whisper('typing', {
						name: self.title,
					});
				}, 300);
			},
			done() {
				this.message = '';
				$('#user-file').val('');
				NProgress.done();
				$(".wb-chat-log-wrapper")
				.stop().
				animate({ scrollTop: $(".wb-chat-log-wrapper")[0].scrollHeight}, 1000);
			},
			formatDate(d) {
				return Moment(d).format('h:mm a');
			},
			markConversationAsRead() {
				const self = this;

				axios.post(`/conversation/${self.conversationId}/mark-as-read`, {
					'_method': 'PATCH',
					conversationId: self.conversationId
				});
			}
		},
		mounted() {
			if (!this.conversationId) {
				return false;
			}
			const self = this;

			let messages = JSON.parse(this.messages);

			if (messages) {
				self.chatMessages = messages;
			}

			Echo.private(`conversation.${self.conversationId}`)
			.listen('MessageSent', (e) => {
				self.chatMessages.push({
					user_id: e.message.user_id,
					type: e.message.type,
					body: e.message.body,
					time: self.formatDate(e.message.created_at)
				});
				document.getElementById('new-message').play();
				self.markConversationAsRead();
			}).listenForWhisper('typing', (e) => {
				self.someoneIsTyping = true;
				self.userThatIsTyping = e.name;
				setTimeout(function(){self.someoneIsTyping = false;}, 5000);
			});
		},
		updated() {
			$(".wb-chat-log-wrapper")
			.stop().
			animate({ scrollTop: $(".wb-chat-log-wrapper")[0].scrollHeight}, 1000);
		}
	}
</script>
