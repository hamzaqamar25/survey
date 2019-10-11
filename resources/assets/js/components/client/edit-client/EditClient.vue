<template>
	<div>
		<h3 class="mb-4">Edit Client</h3>
		<div class="card">
			<div class="card-body">
				<edit-client-form @updateSuccess="updateSuccess"></edit-client-form>
			</div>
		</div>
	</div>
</template>

<script>
	import EditClientForm from './EditClientForm.vue'

	export default {
		components: {
			'edit-client-form': EditClientForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'client'});
			}
		}
	}
</script>
