<template>
	<div>
		<form @submit.prevent="updatePublisher" >
			<div class="form-group">
				<label for="name">Name</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.name}"
					id="name"
					v-model="form.name"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.name">{{ error.name }}</div>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input
					type="email"
					class="form-control"
					:class="{'is-invalid' : error.email}"
					id="email"
					v-model="form.email"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.email">{{ error.email }}</div>
			</div>
			<div class="form-group">
				<label for="phone_no">Phone No.</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.phone_no}"
					id="phone_no"
					v-model="form.phone_no"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.phone_no">{{ error.phone_no }}</div>
			</div>
			<div class="form-group">
				<label for="location">Location</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.location}"
					id="location"
					v-model="form.location"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.location">{{ error.location }}</div>
			</div>

			<div class="form-group">
				<label for="supcode">Supplier Code</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.supcode}"
					id="supcode"
					v-model="form.supcode"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.supcode">{{ error.supcode }}</div>
			</div>
			
			<div class="form-group">
				<label for="new-password">New Password</label>
				<input
					type="password"
					class="form-control"
					:class="{'is-invalid' : error.new_password}"
					id="new-password"
					v-model="form.new_password"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.new_password">{{ error.new_password }}</div>
			</div>
			<div class="form-group">
				<label for="confirm-new-password">Confirm New Password</label>
				<input
					type="password"
					class="form-control"
					:class="{'is-invalid' : error.confirm_new_password}"
					id="confirm-new-password"
					v-model="form.confirm_new_password"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.confirm_new_password">{{ error.confirm_new_password }}</div>
			</div>

			<button type="submit" class="btn btn-primary" :disabled="loading">
				<span v-show="loading">Updating Supplier</span>
				<span v-show="!loading">Update Supplier</span>
			</button>
			<button type="button" @click='backPublisher' class="btn btn-primary" :disabled="loading">
				<span>Back</span>
			</button>
		</form>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../../config";

	export default {
		data() {
			return {
				loading: false,
				form: {
					name: '',
					email: '',
					phone_no:'',
					location:'',
					supcode:'',
					new_password:'',
					confirm_new_password:''
				},
				error: {
					name: '',
					email: '',
					phone_no:'',
					location:'',
					supcode:'',
					new_password:'',
					confirm_new_password:''
				}
			};
		},
		mounted() {
			this.getPublisher();
		},
		methods: {
			backPublisher() {
				this.$emit('updateSuccess');
			},
			getPublisher() {
				axios.get(api.getPublisher+'/'+this.$route.params.id)
					.then((res) => {
						this.form = res.data.publisher;
					})
			},
			updatePublisher() {
				this.loading = true;
				axios.post(api.updatePublisher, this.form)
					.then((res) => {
						this.loading = false;
						this.$noty.success('Supplier Updated.');
						this.$emit('updateSuccess', res.data);
					})
					.catch(err => {
						(err.response.data.error) && this.$noty.error(err.response.data.error);

						(err.response.data.errors)
							? this.setErrors(err.response.data.errors)
							: this.clearErrors();

						this.loading = false;
					});
			},
			setErrors(errors) {
				this.error.name = errors.name ? errors.name[0] : null;
				this.error.email = errors.email ? errors.email[0] : null;
				this.error.phone_no = errors.phone_no ? errors.phone_no[0] : null;
				this.error.location = errors.location ? errors.location[0] : null;
				this.error.supcode = errors.supcode ? errors.supcode[0] : null;
				this.error.confirm_new_password = errors.confirm_new_password ? errors.confirm_new_password[0] : null;
			},
			clearErrors() {
				this.error.name = null;
				this.error.email = null;
				this.error.phone_no = null;
				this.error.location = null;
				this.error.supcode = null;				
				this.error.confirm_new_password = null;
			}
		}
	}
</script>
