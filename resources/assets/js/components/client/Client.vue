<template>
	<div>
		<h3 class="mb-4">Manage Client</h3>
		<router-link :to="{name: 'client.addClient'}" class="nav-link "  activeClass="active" exact>
						Add Client
					</router-link>
		<div class="card">
			<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Name</th>
			      <th scope="col">Email</th>
			      <th scope="col">Location</th>
			      <th scope="col">Phone Number</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for='client in clients'>
			      <th scope="row">{{client.id}}</th>
			      <td>{{client.name}}</td>
			      <td>{{client.email}}</td>
			      <td>{{client.location}}</td>
			      <td>{{client.phone_no}}</td>
			      <td>
						<a :href="'/client/edit-client/'+client.id" class="nav-link " >Edit</a>
			      </td>
			    </tr>
			  </tbody>
			</table>

		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../config";

	export default {
		data() {
			return {
				clients : []
			};
		},
		computed: mapState({
			user: state => state.auth
		}),
		mounted() {
			this.getAllClient();
		},
		methods: {
			getAllClient() {
				axios.get(api.getAllClient)
					.then((res) => {
						this.clients = res.data.client;
					})
			}
		}
	}
</script>
