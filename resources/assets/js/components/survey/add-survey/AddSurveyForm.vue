<template>
	<div>
		<form @submit.prevent="addSurvey">
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
				<label for="client">Client</label>
				<select class="form-control" :class="{'is-invalid' : error.client}" id="client" v-model="form.client" :disabled="loading">
			        <option v-for="item in clients" :value="item.value">
			            {{item.text}}
			        </option>
			    </select>
				<div class="invalid-feedback" v-show="error.client">{{ error.client }}</div>
			</div>
			<div class="form-group">
				<label for="link">Link</label>
				<input
					type="text"
					class="form-control"
					:class="{'is-invalid' : error.link}"
					id="link"
					v-model="form.link"
					:disabled="loading"
				/>
				<div class="invalid-feedback" v-show="error.link">{{ error.link }}</div>
			</div>
			<div class="form-group">
				<label for="location">Suppliers</label>
				<div class='row'  v-for='(publisher,key) in form.publishers'>
					<div class='col-md-2'>
						<div class="form-group">
							<label for="location">Name</label>
							<select class="form-control"
							 :class="{'is-invalid' : error.publishers[key] && error.publishers[key].name}"
							 v-model="publisher.name" :disabled="loading">
						        <option v-for="item in publishers" :value="item.value">
						            {{item.text}}
						        </option>
						    </select>
							<div class="invalid-feedback" v-show="error.publishers[key] && error.publishers[key].name">Supplier Name is required.</div>
						</div>
					</div>
					<div class='col-md-3'>	
						<div class="form-group">
							<label for="location">Complete</label>
							<input
								type="text"
								class="form-control"
								:class="{'is-invalid' : error.publishers[key] && error.publishers[key].link1}"
								v-model="publisher.link1"
								:disabled="loading"
							/>
							<div class="invalid-feedback" v-show="error.publishers[key] && error.publishers[key].link1">Complete Link is required.</div>
						</div>
					</div>
					<div class='col-md-3'>	
						<div class="form-group">
							<label for="location">Quota Full </label>
							<input
								type="text"
								class="form-control"
								:class="{'is-invalid' : error.publishers[key] && error.publishers[key].link2}"
								v-model="publisher.link2"
								:disabled="loading"
							/>
							<div class="invalid-feedback" v-show="error.publishers[key] && error.publishers[key].link2">Quota Full Link is required.</div>
						</div>
					</div>
					<div class='col-md-3'>	
						<div class="form-group">
							<label for="location">Terminate</label>
							<input
								type="text"
								class="form-control"
								:class="{'is-invalid' : error.publishers[key] && error.publishers[key].link3}"
								v-model="publisher.link3"
								:disabled="loading"
							/>
							<div class="invalid-feedback" v-show="error.publishers[key] && error.publishers[key].link3">Terminate Link is required.</div>
						</div>
					</div>
					<div class='col-md-1'>	
						<div class="form-group">
							<button type="button" style="margin-top: 30px;" @click='removePublishers(key)' class="btn btn-primary" :disabled="loading">
								<span>X</span>
							</button>
						</div>	
					</div>
				</div>	

				<button type="button" @click='addPublishers' class="btn btn-primary" :disabled="loading">
					<span>Add Supplier</span>
				</button>
			</div>
			

			<button type="submit" class="btn btn-primary" :disabled="loading">
				<span v-show="loading">Adding Survey</span>
				<span v-show="!loading">Add Survey</span>
			</button>
			<button type="button" @click='backSurvey' class="btn btn-primary" :disabled="loading">
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
				clients: [],
		        publishers: [],
				form: {
					name: '',
					client: '',
					link:'',
					publishers:[]
				},
				error: {
					name: '',
					client: '',
					link:'',
					publishers:[]
				}
			}
		},
		mounted() {
			this.getClient();
			this.getPublishers();
		},
		methods: {
			backSurvey() {
				this.$emit('updateSuccess');
			},
			removePublishers(key) {
				this.form.publishers.splice(key,1);
			},
			addPublishers() {
				let arr = {
							'name' : '',
							'id' : '',
							'link1' : '',
							'link2' : '',
							'link3' : '',
						}
				this.form.publishers.push(arr);
			},
			getClient() {
				axios.get(api.getAllClient)
					.then((res) => {
						let clients = res.data.client;
						this.clients = [];
						clients.forEach(c => {
							if(c.status == 1) {
								this.clients.push({text: c.name, value: c.id });
							}
						})
					})
			},
			getPublishers() {
				axios.get(api.getAllPublisher)
					.then((res) => {
						this.publishers = res.data.publisher;
						let publishers = res.data.publisher;
						this.publishers = [];
						publishers.forEach(c => {
							if(c.status == 1) {
								this.publishers.push({text: c.name, value: c.id });
							}
						})
					})
			},
			addSurvey() {
				let errorTrue = false;
				if(!this.form.name) {
					this.error.name = "Survey name is required."
					errorTrue = true;
				}
				if(!this.form.client) {
					this.error.client = "Client is required."
					errorTrue = true;
				}
				if(!this.form.link) {
					this.error.link = "Survey Link is required."
					errorTrue = true;
				}

				this.form.publishers.forEach((pub,key) => {
					this.error.publishers[key] = {};
					if(!pub.name) {
						this.error.publishers[key].name = 'Name is required.';
						errorTrue = true;
					}
					if(!pub.link1) {
						this.error.publishers[key].link1 = 'Terminate Link 1 is required.';
						errorTrue = true;
					}
					if(!pub.link2) {
						this.error.publishers[key].link2 = 'Quota Full Link is required.';
						errorTrue = true;
					}
					if(!pub.link1) {
						this.error.publishers[key].link3 = 'Terminate Link is required.';
						errorTrue = true;
					}

				});
				this.$forceUpdate();
				
				if(errorTrue == true) {
					return;
				}
				this.loading = true;
				axios.post(api.addSurvey, this.form)
					.then((res) => {
						this.loading = false;
						this.$noty.success('Survey added successfully.');
						this.$emit('updateSuccess');
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
			},
			clearErrors() {
				this.error.name = null;
				this.error.email = null;
				this.error.phone_no = null;
				this.error.location = null;
			}
		}
	}
</script>
