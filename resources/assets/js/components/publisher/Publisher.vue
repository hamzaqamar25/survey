<template>
	<div>
		<h3 class="mb-4">Manage Publisher</h3>
		<router-link :to="{name: 'publisher.addPublisher'}" class="nav-link "  activeClass="active" exact>
						Add Publisher
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
			    <tr v-for='publisher in publishers'>
			      <th scope="row">{{publisher.id}}</th>
			      <td>{{publisher.name}}</td>
			      <td>{{publisher.email}}</td>
			      <td>{{publisher.location}}</td>
			      <td>{{publisher.phone_no}}</td>
			      <td>
						<a :href="'/publisher/edit-publisher/'+publisher.id" class="nav-link " >Edit</a>
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
				publishers : []
			};
		},
		computed: mapState({
			user: state => state.auth
		}),
		mounted() {
			this.getAllPublisher();
		},
		methods: {
			getAllPublisher() {
				axios.get(api.getAllPublisher)
					.then((res) => {
						this.publishers = res.data.publisher;
					})
			}
		}
	}
</script>
